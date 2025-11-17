import javax.swing.*;
import java.awt.*;
import java.awt.event.*;
import java.sql.*;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.Base64;

public class AdminLogin extends DatabaseConnection {
    private JFrame frame;
    private JTextField txtUsername;
    private JPasswordField txtPassword;
    private JButton btnLogin, btnSignup;
    private JLabel lblForgotPassword, lblChangePassword;


    public void showLogin() {
        frame = new JFrame("Admin Login");
        frame.setSize(500, 400);
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setLocationRelativeTo(null);

        // Main panel
        JPanel mainPanel = new JPanel(new BorderLayout());
        mainPanel.setBackground(new Color(200, 220, 240));



        // Center panel with form
        JPanel panel = new JPanel(new GridBagLayout());
        panel.setOpaque(false);
        panel.setBorder(BorderFactory.createEmptyBorder(20, 20, 20, 20));
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(10, 10, 10, 10);
        gbc.fill = GridBagConstraints.HORIZONTAL;

        Font labelFont = new Font("Arial", Font.BOLD, 16);
        Font fieldFont = new Font("Arial", Font.PLAIN, 14);
        Dimension fieldSize = new Dimension(200, 30);

        // Username
        gbc.gridx = 0; gbc.gridy = 0;
        JLabel lblUsername = new JLabel("Username:");
        lblUsername.setFont(labelFont);
        panel.add(lblUsername, gbc);

        gbc.gridx = 1;
        txtUsername = new JTextField();
        txtUsername.setFont(fieldFont);
        txtUsername.setPreferredSize(fieldSize);
        panel.add(txtUsername, gbc);

        // Password
        gbc.gridx = 0; gbc.gridy = 1;
        JLabel lblPassword = new JLabel("Password:");
        lblPassword.setFont(labelFont);
        panel.add(lblPassword, gbc);

        gbc.gridx = 1;
        txtPassword = new JPasswordField();
        txtPassword.setFont(fieldFont);
        txtPassword.setPreferredSize(fieldSize);
        panel.add(txtPassword, gbc);

        // Buttons
        JPanel buttonPanel = new JPanel();
        buttonPanel.setOpaque(false);

        btnLogin = new JButton("Login");
        btnLogin.setFont(new Font("Arial", Font.BOLD, 16));
        btnLogin.setBackground(new Color(100, 149, 237));
        btnLogin.setForeground(Color.WHITE);
        btnLogin.setPreferredSize(new Dimension(100, 35));

        btnSignup = new JButton("Sign Up");
        btnSignup.setFont(new Font("Arial", Font.BOLD, 16));
        btnSignup.setBackground(new Color(60, 179, 113));
        btnSignup.setForeground(Color.WHITE);
        btnSignup.setPreferredSize(new Dimension(120, 35));

        buttonPanel.add(btnLogin);
        buttonPanel.add(btnSignup);

        gbc.gridx = 0; gbc.gridy = 2; gbc.gridwidth = 2;
        gbc.anchor = GridBagConstraints.CENTER;
        panel.add(buttonPanel, gbc);

        // Links (forgot / change password)
        JPanel linkPanel = new JPanel(new FlowLayout());
        linkPanel.setOpaque(false);

        lblForgotPassword = new JLabel("<HTML><U>Forgot Password?</U></HTML>");
        lblForgotPassword.setForeground(Color.BLUE);
        lblForgotPassword.setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));

        lblChangePassword = new JLabel("<HTML><U>Change Password</U></HTML>");
        lblChangePassword.setForeground(Color.BLUE);
        lblChangePassword.setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));

        linkPanel.add(lblForgotPassword);
        linkPanel.add(Box.createHorizontalStrut(20));
        linkPanel.add(lblChangePassword);

        gbc.gridx = 0; gbc.gridy = 3; gbc.gridwidth = 2;
        gbc.anchor = GridBagConstraints.CENTER;
        panel.add(linkPanel, gbc);

        mainPanel.add(panel, BorderLayout.CENTER);
        frame.add(mainPanel);
        frame.setVisible(true);

        // Actions
        btnLogin.addActionListener(e -> loginAdmin());
        btnSignup.addActionListener(e -> {
            frame.dispose();
            new AdminSignUp().showSignup();
        });

        lblForgotPassword.addMouseListener(new MouseAdapter() {
            @Override
            public void mouseClicked(MouseEvent e) {
                frame.dispose();
                new AdminForgotPassword().showForgotPassword();
            }
        });

        lblChangePassword.addMouseListener(new MouseAdapter() {
            @Override
            public void mouseClicked(MouseEvent e) {
                frame.dispose();
                new AdminChangePassword().showChangePassword();
            }
        });
    }

    private void loginAdmin() {
        String username = txtUsername.getText();
        String password = new String(txtPassword.getPassword());


        if (username.isEmpty() || password.isEmpty()) {
            JOptionPane.showMessageDialog(frame, "Enter both username and password!");
            return;
        }

        try (Connection conn = DBConnection.getConnection()) {
            String sql = "SELECT * FROM admins WHERE username=?";
            PreparedStatement ps = conn.prepareStatement(sql);
            ps.setString(1, username);
            ResultSet rs = ps.executeQuery();

            if (rs.next()) {
                String storedHash = rs.getString("password");
                String enteredHash = hashPassword(password);

                if (storedHash.equals(enteredHash)) {
                    JOptionPane.showMessageDialog(frame,
                            "Login successful! Welcome Admin: " + rs.getString("full_name"));
                    frame.dispose();
                    new Landing(rs.getString("full_name"));
                } else {
                    JOptionPane.showMessageDialog(frame, "Invalid username or password!");
                }
            } else {
                JOptionPane.showMessageDialog(frame, "Invalid username or password!");
            }
        } catch (SQLException ex) {
            JOptionPane.showMessageDialog(frame, "Error: " + ex.getMessage());
        }
    }

    // Same hashing method as in AdminSignUp
    private String hashPassword(String password) {
        try {
            MessageDigest md = MessageDigest.getInstance("SHA-256");
            byte[] hashBytes = md.digest(password.getBytes());
            return Base64.getEncoder().encodeToString(hashBytes);
        } catch (NoSuchAlgorithmException e) {
            throw new RuntimeException("Error hashing password", e);
        }
    }

}

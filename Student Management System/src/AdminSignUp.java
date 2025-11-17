import javax.swing.*;
import java.awt.*;
import java.sql.*;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.Base64;

public class AdminSignUp extends DatabaseConnection {
    private JFrame frame;
    private JTextField txtUsername, txtFullName, txtEmail;
    private JPasswordField txtPassword, txtConfirmPassword;
    private JButton btnSignup, btnBack;

    public void showSignup() {
        frame = new JFrame("Admin Signup");
        frame.setSize(500, 450);
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setLocationRelativeTo(null);

        JPanel mainPanel = new JPanel(new BorderLayout());
        mainPanel.setBackground(new Color(200, 220, 240));
        frame.setContentPane(mainPanel);

        JPanel formPanel = new JPanel(new GridBagLayout());
        formPanel.setOpaque(false);
        formPanel.setBorder(BorderFactory.createEmptyBorder(20, 20, 20, 20));
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(10, 10, 10, 10);
        gbc.fill = GridBagConstraints.HORIZONTAL;

        Font labelFont = new Font("Arial", Font.BOLD, 16);
        Font fieldFont = new Font("Arial", Font.PLAIN, 14);
        Dimension fieldSize = new Dimension(200, 30);

        // Full Name
        gbc.gridx = 0; gbc.gridy = 0;
        JLabel lblFullName = new JLabel("Full Name:");
        lblFullName.setFont(labelFont);
        formPanel.add(lblFullName, gbc);
        gbc.gridx = 1;
        txtFullName = new JTextField();
        txtFullName.setFont(fieldFont);
        txtFullName.setPreferredSize(fieldSize);
        formPanel.add(txtFullName, gbc);

        // Email
        gbc.gridx = 0; gbc.gridy = 1;
        JLabel lblEmail = new JLabel("Email:");
        lblEmail.setFont(labelFont);
        formPanel.add(lblEmail, gbc);
        gbc.gridx = 1;
        txtEmail = new JTextField();
        txtEmail.setFont(fieldFont);
        txtEmail.setPreferredSize(fieldSize);
        formPanel.add(txtEmail, gbc);

        // Username
        gbc.gridx = 0; gbc.gridy = 2;
        JLabel lblUsername = new JLabel("Username:");
        lblUsername.setFont(labelFont);
        formPanel.add(lblUsername, gbc);
        gbc.gridx = 1;
        txtUsername = new JTextField();
        txtUsername.setFont(fieldFont);
        txtUsername.setPreferredSize(fieldSize);
        formPanel.add(txtUsername, gbc);

        // Password
        gbc.gridx = 0; gbc.gridy = 3;
        JLabel lblPassword = new JLabel("Password:");
        lblPassword.setFont(labelFont);
        formPanel.add(lblPassword, gbc);
        gbc.gridx = 1;
        txtPassword = new JPasswordField();
        txtPassword.setFont(fieldFont);
        txtPassword.setPreferredSize(fieldSize);
        formPanel.add(txtPassword, gbc);

        // Confirm Password
        gbc.gridx = 0; gbc.gridy = 4;
        JLabel lblConfirmPassword = new JLabel("Confirm Password:");
        lblConfirmPassword.setFont(labelFont);
        formPanel.add(lblConfirmPassword, gbc);
        gbc.gridx = 1;
        txtConfirmPassword = new JPasswordField();
        txtConfirmPassword.setFont(fieldFont);
        txtConfirmPassword.setPreferredSize(fieldSize);
        formPanel.add(txtConfirmPassword, gbc);

        // Buttons
        JPanel buttonPanel = new JPanel();
        buttonPanel.setOpaque(false);
        btnSignup = new JButton("Sign Up");
        btnSignup.setFont(new Font("Arial", Font.BOLD, 16));
        btnSignup.setBackground(new Color(100, 149, 237));
        btnSignup.setForeground(Color.WHITE);
        btnSignup.setPreferredSize(new Dimension(120, 35));
        btnBack = new JButton("Back to Login");
        btnBack.setFont(new Font("Arial", Font.BOLD, 16));
        btnBack.setBackground(new Color(60, 179, 113));
        btnBack.setForeground(Color.WHITE);
        btnBack.setPreferredSize(new Dimension(150, 35));
        buttonPanel.add(btnSignup);
        buttonPanel.add(btnBack);
        gbc.gridx = 0; gbc.gridy = 5; gbc.gridwidth = 2; gbc.anchor = GridBagConstraints.CENTER;
        formPanel.add(buttonPanel, gbc);
        mainPanel.add(formPanel, BorderLayout.CENTER);

        btnSignup.addActionListener(e -> registerAdmin());
        btnBack.addActionListener(e -> {
            frame.dispose();
            new AdminLogin().showLogin();
        });

        frame.setVisible(true);
    }

    private void registerAdmin() {
        if (!validate()) {
            return;
        }
        String username = txtUsername.getText().trim();
        String password = new String(txtPassword.getPassword());
        String confirmPassword = new String(txtConfirmPassword.getPassword());
        String fullName = txtFullName.getText().trim();
        String email = txtEmail.getText().trim();

        if (username.isEmpty() || password.isEmpty() || fullName.isEmpty() || email.isEmpty()) {
            JOptionPane.showMessageDialog(frame, "All fields are required!");
            return;
        }

        if (!password.equals(confirmPassword)) {
            JOptionPane.showMessageDialog(frame, "Passwords do not match!");
            return;
        }

        try (Connection conn = DBConnection.getConnection()) {
            // Check if username already exists
            String checkUserSql = "SELECT * FROM admins WHERE username = ?";
            PreparedStatement checkUserStmt = conn.prepareStatement(checkUserSql);
            checkUserStmt.setString(1, username);
            ResultSet rsUser = checkUserStmt.executeQuery();
            if (rsUser.next()) {
                JOptionPane.showMessageDialog(frame, "Username already exists. Please choose another.");
                return;
            }

            // Check if email already exists
            String checkEmailSql = "SELECT * FROM admins WHERE email = ?";
            PreparedStatement checkEmailStmt = conn.prepareStatement(checkEmailSql);
            checkEmailStmt.setString(1, email);
            ResultSet rsEmail = checkEmailStmt.executeQuery();
            if (rsEmail.next()) {
                JOptionPane.showMessageDialog(frame, "Email already exists. Please use another.");
                return;
            }

            //If both are unique → save new admin
            String hashedPassword = hashPassword(password);
            String sql = "INSERT INTO admins (username, password, full_name, email) VALUES (?, ?, ?, ?)";
            PreparedStatement ps = conn.prepareStatement(sql);
            ps.setString(1, username);
            ps.setString(2, hashedPassword);
            ps.setString(3, fullName);
            ps.setString(4, email);
            ps.executeUpdate();

            JOptionPane.showMessageDialog(frame, "Admin registered successfully!");
            frame.dispose();
            new AdminLogin().showLogin();
        } catch (SQLException ex) {
            JOptionPane.showMessageDialog(frame, "Error: " + ex.getMessage());
        }
    }

    private String hashPassword(String password) {
        try {
            MessageDigest md = MessageDigest.getInstance("SHA-256");
            byte[] hashBytes = md.digest(password.getBytes());
            return Base64.getEncoder().encodeToString(hashBytes);
        } catch (NoSuchAlgorithmException e) {
            throw new RuntimeException("Error hashing password", e);
        }
    }
    private boolean validate(){
        if (!Validations.isValidEmail(txtEmail)) {
            JOptionPane.showMessageDialog(frame, "Invalid email format.");
        }
        return false;
    }
}

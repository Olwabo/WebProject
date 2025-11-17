import javax.swing.*;
import java.awt.*;
import java.sql.*;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.Base64;

public class AdminForgotPassword extends DatabaseConnection {
    private JFrame frame;
    private JTextField txtUsername, txtEmail;
    private JPasswordField txtNewPassword, txtConfirmPassword;
    private JButton btnReset, btnBack;


    public void showForgotPassword() {
        frame = new JFrame("Forgot Password");
        frame.setSize(500, 400);
        frame.setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
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

        // Username
        gbc.gridx = 0; gbc.gridy = 0;
        JLabel lblUsername = new JLabel("Username:");
        lblUsername.setFont(labelFont);
        formPanel.add(lblUsername, gbc);

        gbc.gridx = 1;
        txtUsername = new JTextField();
        txtUsername.setFont(fieldFont);
        txtUsername.setPreferredSize(fieldSize);
        formPanel.add(txtUsername, gbc);

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

        // New Password
        gbc.gridx = 0; gbc.gridy = 2;
        JLabel lblNewPassword = new JLabel("New Password:");
        lblNewPassword.setFont(labelFont);
        formPanel.add(lblNewPassword, gbc);

        gbc.gridx = 1;
        txtNewPassword = new JPasswordField();
        txtNewPassword.setFont(fieldFont);
        txtNewPassword.setPreferredSize(fieldSize);
        formPanel.add(txtNewPassword, gbc);

        // Confirm Password
        gbc.gridx = 0; gbc.gridy = 3;
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

        btnReset = new JButton("Reset Password");
        btnReset.setFont(new Font("Arial", Font.BOLD, 16));
        btnReset.setBackground(new Color(100, 149, 237));
        btnReset.setForeground(Color.WHITE);
        btnReset.setPreferredSize(new Dimension(180, 35));

        btnBack = new JButton("Back");
        btnBack.setFont(new Font("Arial", Font.BOLD, 16));
        btnBack.setBackground(new Color(60, 179, 113));
        btnBack.setForeground(Color.WHITE);
        btnBack.setPreferredSize(new Dimension(100, 35));

        buttonPanel.add(btnReset);
        buttonPanel.add(btnBack);

        gbc.gridx = 0; gbc.gridy = 4; gbc.gridwidth = 2;
        gbc.anchor = GridBagConstraints.CENTER;
        formPanel.add(buttonPanel, gbc);

        mainPanel.add(formPanel, BorderLayout.CENTER);

        // --- Actions ---
        btnReset.addActionListener(e -> resetPassword());
        btnBack.addActionListener(e -> {
            new AdminLogin().showLogin();
            frame.dispose();
        });

        frame.setVisible(true);
    }

    private void resetPassword() {
        String username = txtUsername.getText();
        String email = txtEmail.getText();
        String newPass = new String(txtNewPassword.getPassword());
        String confirm = new String(txtConfirmPassword.getPassword());

        if (username.isEmpty() || email.isEmpty() || newPass.isEmpty() || confirm.isEmpty()) {
            JOptionPane.showMessageDialog(frame, "All fields are required!");
            return;
        }

        if (!newPass.equals(confirm)) {
            JOptionPane.showMessageDialog(frame, "Passwords do not match!");
            return;
        }

        try (Connection conn = DBConnection.getConnection()) {
            // Verify username and email
            String checkSql = "SELECT * FROM admins WHERE username=? AND email=?";
            PreparedStatement checkStmt = conn.prepareStatement(checkSql);
            checkStmt.setString(1, username);
            checkStmt.setString(2, email);
            ResultSet rs = checkStmt.executeQuery();

            if (rs.next()) {
                // Hash new password
                String hashedPassword = hashPassword(newPass);

                // Update password
                String updateSql = "UPDATE admins SET password=? WHERE username=?";
                PreparedStatement updateStmt = conn.prepareStatement(updateSql);
                updateStmt.setString(1, hashedPassword);
                updateStmt.setString(2, username);
                updateStmt.executeUpdate();

                JOptionPane.showMessageDialog(frame, "Password reset successfully!");
                frame.dispose();
                new AdminLogin().showLogin();
            } else {
                JOptionPane.showMessageDialog(frame, "Username or Email not found!");
            }
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



}

import javax.swing.*;
import java.awt.*;
import java.sql.*;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.Base64;

public class AdminChangePassword extends DatabaseConnection {
    private JFrame frame;
    private JTextField txtUsername;
    private JPasswordField txtCurrent, txtNew, txtConfirm;
    private JButton btnChange, btnBack;

    public void showChangePassword() {
        frame = new JFrame("Change Password");
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

        // Current Password
        gbc.gridx = 0; gbc.gridy = 1;
        JLabel lblCurrent = new JLabel("Current Password:");
        lblCurrent.setFont(labelFont);
        formPanel.add(lblCurrent, gbc);

        gbc.gridx = 1;
        txtCurrent = new JPasswordField();
        txtCurrent.setFont(fieldFont);
        txtCurrent.setPreferredSize(fieldSize);
        formPanel.add(txtCurrent, gbc);

        // New Password
        gbc.gridx = 0; gbc.gridy = 2;
        JLabel lblNew = new JLabel("New Password:");
        lblNew.setFont(labelFont);
        formPanel.add(lblNew, gbc);

        gbc.gridx = 1;
        txtNew = new JPasswordField();
        txtNew.setFont(fieldFont);
        txtNew.setPreferredSize(fieldSize);
        formPanel.add(txtNew, gbc);

        // Confirm Password
        gbc.gridx = 0; gbc.gridy = 3;
        JLabel lblConfirm = new JLabel("Confirm New Password:");
        lblConfirm.setFont(labelFont);
        formPanel.add(lblConfirm, gbc);

        gbc.gridx = 1;
        txtConfirm = new JPasswordField();
        txtConfirm.setFont(fieldFont);
        txtConfirm.setPreferredSize(fieldSize);
        formPanel.add(txtConfirm, gbc);

        // Buttons
        JPanel buttonPanel = new JPanel();
        buttonPanel.setOpaque(false);

        btnChange = new JButton("Change Password");
        btnChange.setFont(new Font("Arial", Font.BOLD, 16));
        btnChange.setBackground(new Color(100, 149, 237));
        btnChange.setForeground(Color.WHITE);
        btnChange.setPreferredSize(new Dimension(180, 35));

        btnBack = new JButton("Back");
        btnBack.setFont(new Font("Arial", Font.BOLD, 16));
        btnBack.setBackground(new Color(60, 179, 113));
        btnBack.setForeground(Color.WHITE);
        btnBack.setPreferredSize(new Dimension(100, 35));

        buttonPanel.add(btnChange);
        buttonPanel.add(btnBack);

        gbc.gridx = 0; gbc.gridy = 4; gbc.gridwidth = 2;
        gbc.anchor = GridBagConstraints.CENTER;
        formPanel.add(buttonPanel, gbc);

        mainPanel.add(formPanel, BorderLayout.CENTER);

        // Actions
        btnChange.addActionListener(e -> changePassword());
        btnBack.addActionListener(e -> {
            frame.dispose();
            new AdminLogin().showLogin();
        });

        frame.setVisible(true);
    }

    private void changePassword() {
        String username = txtUsername.getText();
        String current = new String(txtCurrent.getPassword());
        String newPass = new String(txtNew.getPassword());
        String confirm = new String(txtConfirm.getPassword());

        if (username.isEmpty() || current.isEmpty() || newPass.isEmpty() || confirm.isEmpty()) {
            JOptionPane.showMessageDialog(frame, "All fields are required!");
            return;
        }

        if (!newPass.equals(confirm)) {
            JOptionPane.showMessageDialog(frame, "New passwords do not match!");
            return;
        }

        try (Connection conn = DBConnection.getConnection()) {
            // Hash current password
            String hashedCurrent = hashPassword(current);

            // Check current password for the username
            String checkSql = "SELECT password FROM admins WHERE username=?";
            PreparedStatement checkStmt = conn.prepareStatement(checkSql);
            checkStmt.setString(1, username);
            ResultSet rs = checkStmt.executeQuery();

            if (rs.next()) {
                String stored = rs.getString("password");
                if (!stored.equals(hashedCurrent)) {
                    JOptionPane.showMessageDialog(frame, "Current password is incorrect!");
                    return;
                }
            } else {
                JOptionPane.showMessageDialog(frame, "Username not found!");
                return;
            }

            // Hash new password
            String hashedNew = hashPassword(newPass);

            // Update password
            String updateSql = "UPDATE admins SET password=? WHERE username=?";
            PreparedStatement updateStmt = conn.prepareStatement(updateSql);
            updateStmt.setString(1, hashedNew);
            updateStmt.setString(2, username);
            updateStmt.executeUpdate();

            JOptionPane.showMessageDialog(frame, "Password changed successfully!");
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
}

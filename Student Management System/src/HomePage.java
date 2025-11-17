import javax.swing.*;
import java.awt.*;
import java.text.SimpleDateFormat;
import java.util.Date;

public class HomePage {
    private JFrame frame;
    private JLabel lblTime;

    public void showHome() {
        frame = new JFrame("Student Management System - Home");
        frame.setSize(800, 450);
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setLocationRelativeTo(null);
        frame.setLayout(new BorderLayout());

        // --- Top Panel with Time ---
        JPanel topPanel = new JPanel(new BorderLayout());
        topPanel.setBackground(new Color(70, 130, 180));
        topPanel.setBorder(BorderFactory.createEmptyBorder(10, 20, 10, 20));

        JLabel lblTitle = new JLabel("Student Management System");
        lblTitle.setFont(new Font("Arial", Font.BOLD, 22));
        lblTitle.setForeground(Color.WHITE);
        lblTitle.setHorizontalAlignment(SwingConstants.LEFT);

        lblTime = new JLabel();
        lblTime.setFont(new Font("Arial", Font.BOLD, 16));
        lblTime.setForeground(Color.WHITE);
        lblTime.setHorizontalAlignment(SwingConstants.RIGHT);

        topPanel.add(lblTitle, BorderLayout.WEST);
        topPanel.add(lblTime, BorderLayout.EAST);
        frame.add(topPanel, BorderLayout.NORTH);

        // --- Center Panel with Info ---
        JPanel centerPanel = new JPanel(new BorderLayout());
        centerPanel.setBackground(new Color(230, 240, 250));

        JTextArea txtInfo = new JTextArea(
                "***** Welcome to the Student Management System *****\n\n" +
                        "This system helps administrators manage:\n\n" +
                        " - Student records\n" +
                        " - Course information\n" +
                        " - Student grades & performance\n\n" +
                        "Please click the button below to enroll and access the system."
        );
        txtInfo.setFont(new Font("Segoe UI", Font.PLAIN, 16));
        txtInfo.setEditable(false);
        txtInfo.setWrapStyleWord(true);
        txtInfo.setLineWrap(true);
        txtInfo.setOpaque(false);
        txtInfo.setBorder(BorderFactory.createEmptyBorder(20, 40, 20, 40));

        centerPanel.add(txtInfo, BorderLayout.CENTER);
        frame.add(centerPanel, BorderLayout.CENTER);

        // --- Bottom Panel with Enroll Button ---
        JPanel bottomPanel = new JPanel();
        bottomPanel.setOpaque(false);
        bottomPanel.setBorder(BorderFactory.createEmptyBorder(20, 0, 20, 0));

        JButton btnEnroll = new JButton("Enroll");
        btnEnroll.setFont(new Font("Arial", Font.BOLD, 18));
        btnEnroll.setBackground(new Color(70, 130, 180));
        btnEnroll.setForeground(Color.WHITE);
        btnEnroll.setFocusPainted(false);
        btnEnroll.setPreferredSize(new Dimension(160, 50));
        btnEnroll.setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));

        // Hover effect
        btnEnroll.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseEntered(java.awt.event.MouseEvent evt) {
                btnEnroll.setBackground(new Color(100, 149, 237));
            }

            public void mouseExited(java.awt.event.MouseEvent evt) {
                btnEnroll.setBackground(new Color(70, 130, 180));
            }
        });

        bottomPanel.add(btnEnroll);
        frame.add(bottomPanel, BorderLayout.SOUTH);

        // --- Button action ---
        btnEnroll.addActionListener(e -> {
            frame.dispose();
            new AdminLogin().showLogin();
        });

        // --- Timer for live clock ---
        Timer timer = new Timer(1000, e -> updateTime());
        timer.start();
        updateTime();

        frame.setVisible(true);
    }

    private void updateTime() {
        SimpleDateFormat sdf = new SimpleDateFormat("EEEE, dd MMM yyyy  HH:mm:ss");
        lblTime.setText(sdf.format(new Date()));
    }

    public static void main(String[] args) {
        new HomePage().showHome();
    }
}

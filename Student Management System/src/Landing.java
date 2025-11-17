import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

public class Landing extends JFrame {

    public Landing(String adminName) {

        setTitle("Admin Dashboard");
        setSize(600, 400);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLocationRelativeTo(null);
        setLayout(new BorderLayout());

        // Set background color for the whole window
        getContentPane().setBackground(new Color(230, 240, 255)); // light blue


        JLabel welcomeLabel = new JLabel(
                "<html><h2>Welcome " + adminName + "!</h2>" +
                        "<p>This is where you manage everything: students, courses, and grades.</p></html>",
                SwingConstants.CENTER);

        // Create a panel with background color
        JPanel centerPanel = new JPanel(new BorderLayout());
        centerPanel.setBackground(new Color(200, 220, 240)); // slightly darker blue
        centerPanel.add(welcomeLabel, BorderLayout.CENTER);

        add(centerPanel, BorderLayout.CENTER);

        // Menu bar setup
        JMenuBar bar = new JMenuBar();
        bar.setBackground(new Color(180, 200, 220)); // menu bar color

        // Student menu
        JMenu studentMenu = new JMenu("Student");
        JMenuItem manageStudent = new JMenuItem("Manage");
        studentMenu.add(manageStudent);

        // Course menu
        JMenu courseMenu = new JMenu("Course");
        JMenuItem createCourse = new JMenuItem("Create");

        courseMenu.add(createCourse);

        // Grade menu
        JMenu gradeMenu = new JMenu("Grade");
        JMenuItem manageGrade = new JMenuItem("Manage");
        gradeMenu.add(manageGrade);


        // Add menus to bar
        bar.add(studentMenu);
        bar.add(courseMenu);
        bar.add(gradeMenu);
        setJMenuBar(bar);

        // Menu item actions
        manageStudent.addActionListener(e -> {
            Student studentPage = new Student(adminName);
            studentPage.showGUI();
            dispose();
        });


        createCourse.addActionListener(e -> {
            Course coursePage = new Course(adminName); // your course page class
            coursePage.showCourse();
            dispose();
        });


        manageGrade.addActionListener(e -> {
            Grade gradePage = new Grade(adminName); // your grade page class
            gradePage.showGrade();
            dispose();
        });


        setVisible(true);
    }

}


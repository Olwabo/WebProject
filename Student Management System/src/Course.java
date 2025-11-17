import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.sql.*;

public class Course extends DatabaseConnection {
    private JFrame frame;
    private JTextField txtName, txtCode, txtCredit, txtDepartment;
    private JTable courseTable;
    private DefaultTableModel tableModel;
    private String adminName;

    public Course(String adminName){
        this.adminName = adminName;
    }
    public Course() {
        showCourse();
    }

    public void showCourse() {
        // Create the JFrame
        frame = new JFrame("Course Management System");
        frame.setSize(700, 450);
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setLayout(new BorderLayout());

        // Set overall background color (same as Student page)
        frame.getContentPane().setBackground(new Color(230, 240, 255)); // light blue

        // Panel for input fields
        JPanel inputPanel = new JPanel(new GridLayout(4, 2, 5, 5));
        inputPanel.setBackground(new Color(200, 220, 240)); // slightly darker blue

        inputPanel.add(new JLabel("Course Code:"));
        txtCode = new JTextField();
        inputPanel.add(txtCode);

        inputPanel.add(new JLabel("Course Name:"));
        txtName = new JTextField();
        inputPanel.add(txtName);

        inputPanel.add(new JLabel("Credits:"));
        txtCredit = new JTextField();
        inputPanel.add(txtCredit);

        inputPanel.add(new JLabel("Department:"));
        txtDepartment = new JTextField();
        inputPanel.add(txtDepartment);

        frame.add(inputPanel, BorderLayout.NORTH);

        // Table for displaying course data
        String[] columnNames = {"Course Code", "Course Name", "Credits", "Department"};
        tableModel = new DefaultTableModel(columnNames, 0);
        courseTable = new JTable(tableModel);
        JScrollPane tableScroll = new JScrollPane(courseTable);
        tableScroll.getViewport().setBackground(Color.WHITE); // white background for table
        frame.add(tableScroll, BorderLayout.CENTER);

        // Panel for buttons
        JPanel buttonPanel = new JPanel();
        buttonPanel.setBackground(new Color(180, 200, 220)); // another shade of blue

        JButton addButton = new JButton("Add Course");
        addButton.setToolTipText("Click to add course");
        JButton updateButton = new JButton("Update Course");
        updateButton.setToolTipText("Click to update course");
        JButton deleteButton = new JButton("Delete Course");
        deleteButton.setToolTipText("Click to delete course");
        JButton refreshButton = new JButton("Refresh");
        refreshButton.setToolTipText("Click to refresh courses");
        JButton clearButton = new JButton("Clear");
        clearButton.setToolTipText("Click to clear fields");
        JButton backButton = new JButton("Back");
        backButton.setToolTipText("Click to go back to menu");

        buttonPanel.add(addButton);
        buttonPanel.add(updateButton);
        buttonPanel.add(deleteButton);
        buttonPanel.add(refreshButton);
        buttonPanel.add(clearButton);
        buttonPanel.add(backButton);

        frame.add(buttonPanel, BorderLayout.SOUTH);

        // Action listeners
        addButton.addActionListener(e -> addCourse());
        updateButton.addActionListener(e -> updateCourse());
        deleteButton.addActionListener(e -> deleteCourse());
        refreshButton.addActionListener(e -> loadCourses());
        clearButton.addActionListener(e -> clearFields());
        backButton.addActionListener(e -> {
            frame.dispose(); // close current form
            new Landing(adminName); // open dashboard with name
        });

        // Load data when window opens
        loadCourses();
        frame.setVisible(true);
    }

    private void clearFields() {
        clearTexts();
    }

    // Add course
    private void addCourse() {
        if (!validateFields()) {
            return;
        }
        try (Connection conn = DBConnection.getConnection()) {
            String sql = "INSERT INTO courses (course_code, course_name, credits, department) VALUES (?, ?, ?, ?)";
            PreparedStatement ps = conn.prepareStatement(sql);
            ps.setString(1, txtCode.getText());
            ps.setString(2, txtName.getText());
            ps.setInt(3, Integer.parseInt(txtCredit.getText()));
            ps.setString(4, txtDepartment.getText());
            ps.executeUpdate();

            JOptionPane.showMessageDialog(frame, "Course added successfully!");
            loadCourses();
        } catch (SQLException ex) {
            JOptionPane.showMessageDialog(frame, "Error: " + ex.getMessage());
        }
    }

    // Update course
    private void updateCourse() {
        int selectedRow = courseTable.getSelectedRow();
        if (selectedRow >= 0) {
            try (Connection conn = DBConnection.getConnection()) {
                String sql = "UPDATE courses SET course_name=?, credits=?, department=? WHERE course_code=?";
                PreparedStatement ps = conn.prepareStatement(sql);
                ps.setString(1, txtName.getText());
                ps.setInt(2, Integer.parseInt(txtCredit.getText()));
                ps.setString(3, txtDepartment.getText());
                ps.setString(4, txtCode.getText());
                ps.executeUpdate();

                JOptionPane.showMessageDialog(frame, "Course updated successfully!");
                loadCourses();
            } catch (SQLException ex) {
                JOptionPane.showMessageDialog(frame, "Error: " + ex.getMessage());
            }
        } else {
            JOptionPane.showMessageDialog(frame, "Select a course to update.");
        }
    }

    // Delete course
    private void deleteCourse() {
        int selectedRow = courseTable.getSelectedRow();
        if (selectedRow >= 0) {
            String courseCode = (String) tableModel.getValueAt(selectedRow, 0);
            try (Connection conn = DBConnection.getConnection()) {
                String sql = "DELETE FROM courses WHERE course_code=?";
                PreparedStatement ps = conn.prepareStatement(sql);
                ps.setString(1, courseCode);
                ps.executeUpdate();

                JOptionPane.showMessageDialog(frame, "Course deleted successfully!");
                loadCourses();
            } catch (SQLException ex) {
                JOptionPane.showMessageDialog(frame, "Error: " + ex.getMessage());
            }
        } else {
            JOptionPane.showMessageDialog(frame, "Select a course to delete.");
        }
    }

    // Load courses into table
    private void loadCourses() {
        try (Connection conn = DBConnection.getConnection()) {
            String sql = "SELECT * FROM courses";
            Statement stmt = conn.createStatement();
            ResultSet rs = stmt.executeQuery(sql);

            tableModel.setRowCount(0); // clear old data
            while (rs.next()) {
                tableModel.addRow(new Object[]{
                        rs.getString("course_code"),
                        rs.getString("course_name"),
                        rs.getInt("credits"),
                        rs.getString("department")
                });
            }
        } catch (SQLException ex) {
            JOptionPane.showMessageDialog(frame, "Error: " + ex.getMessage());
        }
    }

    private boolean validateFields() {
        if (Validations.isEmpty(txtCode) ||
                Validations.isEmpty(txtName) ||
                Validations.isEmpty(txtCredit) ||
                Validations.isEmpty(txtDepartment)) {

            JOptionPane.showMessageDialog(frame, "Please fill in all required fields.");
            return false;
        }
        if (!Validations.isNumericNumber(txtCredit)) {
            JOptionPane.showMessageDialog(frame, "Credits must contain only numeric values.");
            return false;
        }
        return true;
    }
    private boolean clearTexts(){
        Validations.clearTextField(txtCode);
        Validations.clearTextField(txtCredit);
        Validations.clearTextField(txtDepartment);
        Validations.clearTextField(txtName);

        return false;
    }
}

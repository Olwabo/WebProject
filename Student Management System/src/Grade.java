import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.sql.*;
import java.util.HashMap;

public class Grade extends DatabaseConnection {
    private JFrame frame;
    private JTextField txtScore, txtGrade, txtSemester;
    private JTable gradeTable;
    private DefaultTableModel tableModel;
    private JComboBox<String> studentDropdown, courseDropdown;
    private String adminName;
    private HashMap<String, Integer> studentMap = new HashMap<>();
    private HashMap<String, Integer> courseMap = new HashMap<>();

    public Grade(String adminName){
        this.adminName = adminName;
    }

    public void showGrade() {
        frame = new JFrame("Grade Management System");
        frame.setSize(700, 500);
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setLayout(new BorderLayout());

        // Set background color for the whole window
        frame.getContentPane().setBackground(new Color(230, 240, 255)); // light blue

        // Input panel with consistent background
        JPanel inputPanel = new JPanel(new GridLayout(5, 2));
        inputPanel.setBackground(new Color(200, 220, 240)); // slightly darker blue

        inputPanel.add(new JLabel("Student:"));
        studentDropdown = new JComboBox<>();
        inputPanel.add(studentDropdown);

        inputPanel.add(new JLabel("Course:"));
        courseDropdown = new JComboBox<>();
        inputPanel.add(courseDropdown);

        inputPanel.add(new JLabel("Score:"));
        txtScore = new JTextField();
        inputPanel.add(txtScore);

        inputPanel.add(new JLabel("Grade:"));
        txtGrade = new JTextField();
        inputPanel.add(txtGrade);

        inputPanel.add(new JLabel("Semester:"));
        txtSemester = new JTextField();
        inputPanel.add(txtSemester);

        frame.add(inputPanel, BorderLayout.NORTH);

        // Table with scroll pane
        String[] columnNames = {"Student", "Course", "Score", "Grade", "Semester"};
        tableModel = new DefaultTableModel(columnNames, 0);
        gradeTable = new JTable(tableModel);
        JScrollPane tableScroll = new JScrollPane(gradeTable);
        tableScroll.getViewport().setBackground(Color.WHITE); // white background for table
        frame.add(tableScroll, BorderLayout.CENTER);


        // Button panel with consistent background
        JPanel buttonPanel = new JPanel();
        buttonPanel.setBackground(new Color(180, 200, 220)); // same as menu bar color

        JButton addButton = new JButton("Add Grade");
        addButton.setToolTipText("Click to add grade");
        JButton updateButton = new JButton("Update Grade");
        updateButton.setToolTipText("Click to update grade");
        JButton deleteButton = new JButton("Delete Grade");
        deleteButton.setToolTipText("Click to delete grade");
        JButton refreshButton = new JButton("Refresh");
        refreshButton.setToolTipText("Click to refresh grade");
        JButton clearButton = new JButton("Clear");
        clearButton.setToolTipText("Click clear the fields");
        JButton backButton = new JButton("Back");
        backButton.setToolTipText("Click to go back to menu");

        buttonPanel.add(addButton);
        buttonPanel.add(updateButton);
        buttonPanel.add(deleteButton);
        buttonPanel.add(refreshButton);
        buttonPanel.add(clearButton);
        buttonPanel.add(backButton);

        frame.add(buttonPanel, BorderLayout.SOUTH);

        // Button actions
        addButton.addActionListener(e -> addGrade());
        updateButton.addActionListener(e -> updateGrade());
        deleteButton.addActionListener(e -> deleteGrade());
        refreshButton.addActionListener(e -> loadGrades());
        clearButton.addActionListener(e -> clearFields());
        backButton.addActionListener(e -> {
            frame.dispose();
            new Landing(adminName);
        });

        loadStudents();
        loadCourses();
        loadGrades();

        frame.setVisible(true);
    }

    private void clearFields() { clearTexts(); }

    private void loadStudents() {
        try (Connection conn = DBConnection.getConnection()) {
            String sql = "SELECT student_id, student_number, first_name, last_name FROM students";
            Statement stmt = conn.createStatement();
            ResultSet rs = stmt.executeQuery(sql);
            studentDropdown.removeAllItems();
            studentMap.clear();
            while (rs.next()) {
                int id = rs.getInt("student_id");
                String displayName = rs.getString("student_number") + " - "
                        + rs.getString("first_name") + " " + rs.getString("last_name");
                studentDropdown.addItem(displayName);
                studentMap.put(displayName, id);
            }
        } catch (SQLException ex) {
            JOptionPane.showMessageDialog(frame, "Error loading students: " + ex.getMessage());
        }
    }


    private void loadCourses() {
        try (Connection conn = DBConnection.getConnection()) {
            String sql = "SELECT course_id, course_name FROM courses";
            Statement stmt = conn.createStatement();
            ResultSet rs = stmt.executeQuery(sql);
            courseDropdown.removeAllItems();
            courseMap.clear();
            while (rs.next()) {
                int id = rs.getInt("course_id");
                String name = rs.getString("course_name");
                courseDropdown.addItem(name);
                courseMap.put(name, id);
            }
        } catch (SQLException ex) {
            JOptionPane.showMessageDialog(frame, "Error loading courses: " + ex.getMessage());
        }
    }

    private void addGrade() {
        if (!validateFields()) return;
        try (Connection conn = DBConnection.getConnection()) {
            String sql = "INSERT INTO grades (student_id, course_id, score, grade, semester) VALUES (?, ?, ?, ?, ?)";
            PreparedStatement ps = conn.prepareStatement(sql);
            ps.setInt(1, studentMap.get((String) studentDropdown.getSelectedItem()));
            ps.setInt(2, courseMap.get((String) courseDropdown.getSelectedItem()));
            ps.setDouble(3, Double.parseDouble(txtScore.getText()));
            ps.setString(4, txtGrade.getText());
            ps.setString(5, txtSemester.getText());
            ps.executeUpdate();
            JOptionPane.showMessageDialog(frame, "Grade added successfully!");
            loadGrades();
        } catch (SQLException ex) {
            JOptionPane.showMessageDialog(frame, "Error: " + ex.getMessage());
        }
    }

    private void updateGrade() {
        int selectedRow = gradeTable.getSelectedRow();
        if (selectedRow >= 0) {
            int gradeId = (int) tableModel.getValueAt(selectedRow, 0);
            try (Connection conn = DBConnection.getConnection()) {
                String sql = "UPDATE grades SET student_id=?, course_id=?, score=?, grade=?, semester=? WHERE grade_id=?";
                PreparedStatement ps = conn.prepareStatement(sql);
                ps.setInt(1, studentMap.get((String) studentDropdown.getSelectedItem()));
                ps.setInt(2, courseMap.get((String) courseDropdown.getSelectedItem()));
                ps.setDouble(3, Double.parseDouble(txtScore.getText()));
                ps.setString(4, txtGrade.getText());
                ps.setString(5, txtSemester.getText());
                ps.setInt(6, gradeId);
                ps.executeUpdate();
                JOptionPane.showMessageDialog(frame, "Grade updated successfully!");
                loadGrades();
            } catch (SQLException ex) {
                JOptionPane.showMessageDialog(frame, "Error: " + ex.getMessage());
            }
        } else {
            JOptionPane.showMessageDialog(frame, "Select a grade to update.");
        }
    }

    private void deleteGrade() {
        int selectedRow = gradeTable.getSelectedRow();
        if (selectedRow >= 0) {
            int gradeId = (int) tableModel.getValueAt(selectedRow, 0);
            try (Connection conn = DBConnection.getConnection()) {
                String sql = "DELETE FROM grades WHERE grade_id=?";
                PreparedStatement ps = conn.prepareStatement(sql);
                ps.setInt(1, gradeId);
                ps.executeUpdate();
                JOptionPane.showMessageDialog(frame, "Grade deleted successfully!");
                loadGrades();
            } catch (SQLException ex) {
                JOptionPane.showMessageDialog(frame, "Error: " + ex.getMessage());
            }
        } else {
            JOptionPane.showMessageDialog(frame, "Select a grade to delete.");
        }
    }

    private void loadGrades() {
        try (Connection conn = DBConnection.getConnection()) {
            String sql = """
            SELECT g.grade_id,
                   s.student_number,
                   s.first_name,
                   s.last_name,
                   c.course_name,
                   g.score,
                   g.grade,
                   g.semester
            FROM grades g
            JOIN students s ON g.student_id = s.student_id
            JOIN courses c ON g.course_id = c.course_id
        """;
            Statement stmt = conn.createStatement();
            ResultSet rs = stmt.executeQuery(sql);
            tableModel.setRowCount(0);
            while (rs.next()) {
                String studentDisplay = rs.getString("student_number") + " - "
                        + rs.getString("first_name") + " "
                        + rs.getString("last_name");
                tableModel.addRow(new Object[]{

                        studentDisplay,
                        rs.getString("course_name"),
                        rs.getDouble("score"),
                        rs.getString("grade"),
                        rs.getString("semester")
                });
            }
        } catch (SQLException ex) {
            JOptionPane.showMessageDialog(frame, "Error loading grades: " + ex.getMessage());
        }
    }

    private boolean validateFields() {
        if (Validations.isEmpty(txtScore) || Validations.isEmpty(txtGrade) || Validations.isEmpty(txtSemester)) {
            JOptionPane.showMessageDialog(frame, "Please fill in all required fields.");
            return false;
        }
        if (!Validations.isNumericNumber(txtScore)) {
            JOptionPane.showMessageDialog(frame, "Score must contain only numeric values.");
            return false;
        }
        if (!Validations.isValidGrade(txtGrade)) {
            JOptionPane.showMessageDialog(frame, "Invalid grade! Please enter a grade from A+ to F.");
            return false;
        }
        return true;
    }

    private boolean clearTexts(){
        Validations.clearTextField(txtScore);
        Validations.clearTextField(txtSemester);
        Validations.clearTextField(txtGrade);
        return false;
    }
}

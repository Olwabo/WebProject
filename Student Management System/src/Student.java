import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.sql.*;

public class Student extends DatabaseConnection {
    private JFrame frame;
    private JTextField txtstudentId, txtfirstName, txtlastName, txtgender, txtemail, txtphoneNumber, txtaddress, txtdateOfBirth;
    private JTable studentTable;
    private DefaultTableModel tableModel;
    private JComboBox<String> courseDropdown; // Dropdown for course
    private String adminName;

    public Student(String adminName) {
        this.adminName = adminName;
    }

    public void showGUI() {

        // Initialize frame
        frame = new JFrame("Student Management System");
        frame.setSize(900, 550);
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setLayout(new BorderLayout());
        frame.getContentPane().setBackground(new Color(230, 240, 255));
        // Panel for input fields
        JPanel inputPanel = new JPanel(new GridLayout(9, 2, 5, 5));
        inputPanel.setBackground(new Color(200, 220, 240)); // slightly darker blue
        inputPanel.add(new JLabel("Student Number:"));
        txtstudentId = new JTextField();
        inputPanel.add(txtstudentId);

        inputPanel.add(new JLabel("First Name:"));
        txtfirstName = new JTextField();
        inputPanel.add(txtfirstName);

        inputPanel.add(new JLabel("Last Name:"));
        txtlastName = new JTextField();
        inputPanel.add(txtlastName);

        inputPanel.add(new JLabel("Gender:"));
        txtgender = new JTextField();
        inputPanel.add(txtgender);

        inputPanel.add(new JLabel("Date Of Birth (YYYY-MM-DD):"));
        txtdateOfBirth = new JTextField();
        inputPanel.add(txtdateOfBirth);

        inputPanel.add(new JLabel("Email:"));
        txtemail = new JTextField();
        inputPanel.add(txtemail);

        inputPanel.add(new JLabel("Phone Number:"));
        txtphoneNumber = new JTextField();
        inputPanel.add(txtphoneNumber);

        inputPanel.add(new JLabel("Address:"));
        txtaddress = new JTextField();
        inputPanel.add(txtaddress);



        inputPanel.add(new JLabel("Course:"));
        courseDropdown = new JComboBox<>();
        loadCourses(); // Load courses from DB
        inputPanel.add(courseDropdown);

        frame.add(inputPanel, BorderLayout.NORTH);

        // Table
        String[] columnNames = {"Student Number", "First Name", "Last Name", "Gender", "Date Of Birth", "Email", "Phone Number", "Address", "Course"};
        tableModel = new DefaultTableModel(columnNames, 0);
        studentTable = new JTable(tableModel);
        JScrollPane tableScroll = new JScrollPane(studentTable);
        tableScroll.getViewport().setBackground(Color.WHITE); // white background for table
        frame.add(new JScrollPane(studentTable), BorderLayout.CENTER);

        // Buttons
        JPanel buttonPanel = new JPanel();
        buttonPanel.setBackground(new Color(180, 200, 220)); // another shade
        JButton addButton = new JButton("Add Student");
        addButton.setToolTipText("Click to add student");
        JButton updateButton = new JButton("Update Student");
        updateButton.setToolTipText("Click to update student");
        JButton deleteButton = new JButton("Delete Student");
        deleteButton.setToolTipText("Click to delete student");
        JButton searchButton = new JButton("Search Student");
        searchButton.setToolTipText("Click to search student");
        JButton refreshButton = new JButton("Refresh");
        refreshButton.setToolTipText("Click to refresh the data");
        JButton clearButton = new JButton("Clear");
        clearButton.setToolTipText("Click to clear the fields");
        JButton backButton = new JButton("Back");
        backButton.setToolTipText("Click to go back to menu");



        buttonPanel.add(addButton);
        buttonPanel.add(updateButton);
        buttonPanel.add(deleteButton);
        buttonPanel.add(searchButton);
        buttonPanel.add(refreshButton);
        buttonPanel.add(clearButton);
        buttonPanel.add(backButton);

        frame.add(buttonPanel, BorderLayout.SOUTH);

        // Button actions
        addButton.addActionListener(e -> addStudent());
        updateButton.addActionListener(e -> updateStudent());
        deleteButton.addActionListener(e -> deleteStudent());
        searchButton.addActionListener(e -> searchStudent());
        refreshButton.addActionListener(e -> loadStudents());
        backButton.addActionListener(e -> {
            frame.dispose(); // close current form
            new Landing(adminName); // open dashboard with name
        });

        clearButton.addActionListener(e -> clearFields());

        // Load data at startup
        loadStudents();

        frame.setVisible(true);
    }

    // Load courses into dropdown
    private void loadCourses() {
        try (Connection conn = DBConnection.getConnection()) {
            String sql = "SELECT course_id, course_name FROM courses";
            Statement stmt = conn.createStatement();
            ResultSet rs = stmt.executeQuery(sql);
            while (rs.next()) {
                courseDropdown.addItem(rs.getInt("course_id") + " - " + rs.getString("course_name"));
            }
        } catch (SQLException ex) {
            JOptionPane.showMessageDialog(frame, "Error loading courses: " + ex.getMessage());
        }
    }

    // Add student
    private void addStudent() {
        if (!validateFields()) {
            return;
        }
        try (Connection conn = DBConnection.getConnection()) {
            String sql = "INSERT INTO students (student_number, first_name, last_name, gender, date_of_birth, email, phone_number, address, course_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            PreparedStatement ps = conn.prepareStatement(sql);

            ps.setString(1, txtstudentId.getText());
            ps.setString(2, txtfirstName.getText());
            ps.setString(3, txtlastName.getText());
            ps.setString(4, txtgender.getText());
            ps.setString(5, txtdateOfBirth.getText());
            ps.setString(6, txtemail.getText());
            ps.setString(7, txtphoneNumber.getText());
            ps.setString(8, txtaddress.getText());

            String selectedCourse = (String) courseDropdown.getSelectedItem();
            if (selectedCourse != null) {
                String courseId = selectedCourse.split(" - ")[0]; // Extract course_id before " - "
                ps.setInt(9, Integer.parseInt(courseId));
            } else {
                ps.setNull(9, java.sql.Types.INTEGER);
            }

            ps.executeUpdate();
            JOptionPane.showMessageDialog(frame, "Student added successfully!");
            loadStudents();
        } catch (SQLException ex) {
            JOptionPane.showMessageDialog(frame, "Error: " + ex.getMessage());
        }
    }

    // Update student
    private void updateStudent() {
        int selectedRow = studentTable.getSelectedRow();
        if (selectedRow >= 0) {
            try (Connection conn = DBConnection.getConnection()) {
                String sql = "UPDATE students SET first_name=?, last_name=?, gender=?, date_of_birth=?, email=?, phone_number=?, address=?, course_id=? WHERE student_number=?";
                PreparedStatement ps = conn.prepareStatement(sql);

                ps.setString(1, txtfirstName.getText());
                ps.setString(2, txtlastName.getText());
                ps.setString(3, txtgender.getText());
                ps.setString(4, txtdateOfBirth.getText());
                ps.setString(5, txtemail.getText());
                ps.setString(6, txtphoneNumber.getText());
                ps.setString(7, txtaddress.getText());

                String selectedCourse = (String) courseDropdown.getSelectedItem();
                if (selectedCourse != null) {
                    String courseId = selectedCourse.split(" - ")[0];
                    ps.setInt(8, Integer.parseInt(courseId));
                } else {
                    ps.setNull(8, java.sql.Types.INTEGER);
                }

                ps.setString(9, txtstudentId.getText());

                ps.executeUpdate();
                JOptionPane.showMessageDialog(frame, "Student updated successfully!");
                loadStudents();
            } catch (SQLException ex) {
                JOptionPane.showMessageDialog(frame, "Error: " + ex.getMessage());
            }
        } else {
            JOptionPane.showMessageDialog(frame, "Select a student to update.");
        }
    }

    // Delete student
    private void deleteStudent() {
        int selectedRow = studentTable.getSelectedRow();
        if (selectedRow >= 0) {
            String studentNumber = (String) tableModel.getValueAt(selectedRow, 0);
            try (Connection conn = DBConnection.getConnection()) {
                String sql = "DELETE FROM students WHERE student_number=?";
                PreparedStatement ps = conn.prepareStatement(sql);
                ps.setString(1, studentNumber);
                ps.executeUpdate();
                JOptionPane.showMessageDialog(frame, "Student deleted successfully!");
                loadStudents();
            } catch (SQLException ex) {
                JOptionPane.showMessageDialog(frame, "Error: " + ex.getMessage());
            }
        } else {
            JOptionPane.showMessageDialog(frame, "Select a student to delete.");
        }
    }

    // Search student and display course name
    private void searchStudent() {
        String searchTerm = JOptionPane.showInputDialog(frame, "Enter Student Number, First Name, or Last Name:");
        if (searchTerm == null || searchTerm.isBlank()) return;

        try (Connection conn = DBConnection.getConnection()) {
            String sql = "SELECT s.*, c.course_name " +
                    "FROM students s " +
                    "LEFT JOIN courses c ON s.course_id = c.course_id " +
                    "WHERE s.student_number=? OR s.first_name=? OR s.last_name=?";
            PreparedStatement ps = conn.prepareStatement(sql);
            ps.setString(1, searchTerm);
            ps.setString(2, searchTerm);
            ps.setString(3, searchTerm);

            ResultSet rs = ps.executeQuery();
            if (rs.next()) {
                JOptionPane.showMessageDialog(frame, "Student Found:\n" +
                        "Student Number: " + rs.getString("student_number") + "\n" +
                        "Name: " + rs.getString("first_name") + " " + rs.getString("last_name") + "\n" +
                        "Gender: " + rs.getString("gender") + "\n" +
                        "DOB: " + rs.getString("date_of_birth") + "\n" +
                        "Email: " + rs.getString("email") + "\n" +
                        "Phone: " + rs.getString("phone_number") + "\n" +
                        "Address: " + rs.getString("address") + "\n" +
                        "Course: " + rs.getString("course_name")); // course name
            } else {
                JOptionPane.showMessageDialog(frame, "Student not found.");
            }
        } catch (SQLException ex) {
            JOptionPane.showMessageDialog(frame, "Error: " + ex.getMessage());
        }
    }


    // Load students with course names instead of IDs
    private void loadStudents() {
        try (Connection conn = DBConnection.getConnection()) {
            String sql = "SELECT s.student_number, s.first_name, s.last_name, s.gender, s.date_of_birth, " +
                    "s.email, s.phone_number, s.address, c.course_name " +
                    "FROM students s " +
                    "LEFT JOIN courses c ON s.course_id = c.course_id";
            Statement stmt = conn.createStatement();
            ResultSet rs = stmt.executeQuery(sql);

            tableModel.setRowCount(0); // clear table

            while (rs.next()) {
                tableModel.addRow(new Object[]{
                        rs.getString("student_number"),
                        rs.getString("first_name"),
                        rs.getString("last_name"),
                        rs.getString("gender"),
                        rs.getString("date_of_birth"),
                        rs.getString("email"),
                        rs.getString("phone_number"),
                        rs.getString("address"),
                        rs.getString("course_name") // show course name instead of ID
                });
            }
        } catch (SQLException ex) {
            JOptionPane.showMessageDialog(frame, "Error: " + ex.getMessage());
        }
    }


    private void clearFields(){
        if (!clearTexts()) {
            return;
        }
    }
    private boolean validateFields() {
        if (Validations.isEmpty(txtstudentId) ||
                Validations.isEmpty(txtfirstName) ||
                Validations.isEmpty(txtlastName) ||
                Validations.isEmpty(txtgender) ||
                Validations.isEmpty(txtdateOfBirth) ||
                Validations.isEmpty(txtemail) ||
                Validations.isEmpty(txtphoneNumber) ||
                Validations.isEmpty(txtaddress)) {

            JOptionPane.showMessageDialog(frame, "Please fill in all required fields.");
            return false;
        }
        if (!Validations.isValidStudentNumber(txtstudentId)) {
            JOptionPane.showMessageDialog(frame, "Student number must be exactly 9 characters.");
            return false;
        }
        if (!Validations.isValidEmail(txtemail)) {
            JOptionPane.showMessageDialog(frame, "Invalid email format.");
            return false;
        }
        if (!Validations.isNumericNumber(txtstudentId)) {
            JOptionPane.showMessageDialog(frame, "Student number must contain only numeric values.");
            return false;
        }
        if (!Validations.isNumericNumber(txtphoneNumber)) {
            JOptionPane.showMessageDialog(frame, "Phone number must contain only numeric values.");
            return false;
        }
        if (!Validations.isValidPhone(txtphoneNumber)) {
            JOptionPane.showMessageDialog(frame, "Phone number must be exactly 10 digits.");
            return false;
        }
        if (!Validations.isComboBoxSelected(courseDropdown)) {
            JOptionPane.showMessageDialog(frame, "Please select a course.");
            return false;
        }

        return true;
    }
    private boolean clearTexts(){
        Validations.clearTextField(txtstudentId);
        Validations.clearTextField(txtfirstName);
        Validations.clearTextField(txtlastName);
        Validations.clearTextField(txtgender);
        Validations.clearTextField(txtdateOfBirth);
        Validations.clearTextField(txtemail);
        Validations.clearTextField(txtphoneNumber);
        Validations.clearTextField(txtaddress);

        return false;
    }
}

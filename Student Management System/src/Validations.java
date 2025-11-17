import javax.swing.*;
import java.util.regex.*;
public class Validations {
    public static boolean isEmpty(JTextField textField) {
        return textField.getText().trim().isEmpty();
    }

    public static boolean isValidEmail(JTextField textField) {
        String email = textField.getText().trim();
        String emailRegex = "^[A-Za-z0-9+_.-]+@[A-Za-z0-9.-]+$";
        Pattern pattern = Pattern.compile(emailRegex);
        Matcher matcher = pattern.matcher(email);
        return matcher.matches();
    }

    public static boolean isValidPhone(JTextField textField) {
        String phone = textField.getText().trim();
        return phone.matches("\\d{10}"); // Only digits, exactly 10
    }

    public static boolean isNumericNumber(JTextField textField) {
        String number = textField.getText().trim();
        return number.matches("\\d+"); // Only digits, any length
    }

    public static boolean isValidStudentNumber(JTextField textField) {
        String studentNumber = textField.getText().trim();
        return studentNumber.length() == 9; // Must be exactly 9 characters
    }

    public static boolean isComboBoxSelected(JComboBox<?> comboBox) {
        return comboBox.getSelectedItem() != null && !comboBox.getSelectedItem().toString().isEmpty();
    }

    public static boolean isValidGrade(JTextField gradeField) {
        String grade = gradeField.getText().trim().toUpperCase();  // Get text from JTextField
        String[] validGrades = {"A+", "A", "A-", "B+", "B", "B-",
                "C+", "C", "C-", "D+", "D", "D-", "F"};

        for (String g : validGrades) {
            if (g.equals(grade)) {
                return true; // Grade is valid
            }
        }

        return false; // Grade not found in the list
    }
    public static void clearTextField(JTextField textField) {
        textField.setText("");
    }
    
}

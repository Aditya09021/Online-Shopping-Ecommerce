import java.awt.GridLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;

import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JTextField;
import javax.swing.SwingUtilities;

public class os {
    private JFrame frame;
    private JTextField attribute1Field;
    private JTextField attribute2Field;
    private JLabel resultLabel;

    public os() {
        frame = new JFrame("Attribute Capture App");
        frame.setSize(400, 250);
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

        // Create components
        JLabel attribute1Label = new JLabel("Attribute 1:");
        attribute1Field = new JTextField(20);

        JLabel attribute2Label = new JLabel("Attribute 2:");
        attribute2Field = new JTextField(20);

        JButton captureButton = new JButton("Capture");
        captureButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                captureAttributes();
            }
        });

        resultLabel = new JLabel("Result:");

        // Set layout
        frame.setLayout(new GridLayout(4, 2));

        // Add components to the frame
        frame.add(attribute1Label);
        frame.add(attribute1Field);
        frame.add(attribute2Label);
        frame.add(attribute2Field);
        frame.add(captureButton);
        frame.add(resultLabel);

        // Make the frame visible
        frame.setVisible(true);
    }

    private void captureAttributes() {
        String attribute1Text = attribute1Field.getText();
        String attribute2Text = attribute2Field.getText();

        // Validate if the input is numeric
        try {
            int attribute1 = Integer.parseInt(attribute1Text);
            int attribute2 = Integer.parseInt(attribute2Text);

            // Perform subtraction on the captured attributes
            int result = performSubtraction(attribute1, attribute2);

            // Save the result to the database
            saveResultToDatabase(result);

            // Display the result in the front end
            resultLabel.setText("Result: " + result);
        } catch (NumberFormatException ex) {
            JOptionPane.showMessageDialog(frame, "Please enter valid numeric values.");
        }
    }

    private int performSubtraction(int attribute1, int attribute2) {
        // Perform subtraction operation
        return attribute1 - attribute2;
    }

    private void saveResultToDatabase(int result) {
        // JDBC connection parameters
        String jdbcURL = "jdbc:mysql://localhost:3306/aditya";
        String username = "root";
        String password = "aditya@129";

        try (Connection connection = DriverManager.getConnection(jdbcURL, username, password)) {
            // Create the results table if not exists
            String createResultsTableQuery = "CREATE TABLE IF NOT EXISTS results ("
                    + "id INT AUTO_INCREMENT PRIMARY KEY,"
                    + "result INT)";
            try (PreparedStatement preparedStatement = connection.prepareStatement(createResultsTableQuery)) {
                preparedStatement.execute();
            }

            // Insert the result into the results table
            String insertResultQuery = "INSERT INTO results (result) VALUES (?)";
            try (PreparedStatement preparedStatement = connection.prepareStatement(insertResultQuery)) {
                preparedStatement.setInt(1, result);
                preparedStatement.executeUpdate();
            }

            // Inform the user that the result has been saved to the database
            JOptionPane.showMessageDialog(frame, "Result saved to database successfully!");

        } catch (SQLException e) {
            e.printStackTrace();
            JOptionPane.showMessageDialog(frame, "Error saving result to database.");
        }
    }

    public static void main(String[] args) {
        // Register JDBC driver
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
        } catch (ClassNotFoundException e) {
            e.printStackTrace();
            return;
        }

        SwingUtilities.invokeLater(new Runnable() {
            @Override
            public void run() {
                new os();
            }
        });
    }
}

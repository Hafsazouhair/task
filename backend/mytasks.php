<?php
require_once '../connexion.php'; // Include your database connection file here

session_start();

if (!isset($_SESSION['username']) || $_SESSION['role_type'] != 'user') {
    header("Location: login.php");
    exit();
}

// Function to delete a task
function deleteTask($con, $task_id) {
    $sql = "DELETE FROM tasks WHERE task_id = :task_id";

    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':task_id', $task_id);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "<p>Error deleting task: " . $e->getMessage() . "</p>";
    }
}

// Function to edit a task
function editTask($con, $task) {
    $sql = "UPDATE tasks SET task_title = :task_title, task_description = :task_description, priority = :priority, due_date = :due_date, is_completed = :is_completed, ID_category = :ID_category WHERE task_id = :task_id";

    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':task_title', $task['task_title']);
        $stmt->bindParam(':task_description', $task['task_description']);
        $stmt->bindParam(':priority', $task['priority']);
        $stmt->bindParam(':due_date', $task['due_date']);
        $stmt->bindParam(':is_completed', $task['is_completed']);
        $stmt->bindParam(':ID_category', $task['ID_category']);
        $stmt->bindParam(':task_id', $task['task_id']);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "<p>Error updating task: " . $e->getMessage() . "</p>";
    }
}

// Function to add a task
function addTask($con, $task) {
    $sql = "INSERT INTO tasks (task_title, task_description, priority, due_date, is_completed, ID_category, ID_user) VALUES (:task_title, :task_description, :priority, :due_date, :is_completed, :ID_category, :ID_user)";

    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':task_title', $task['task_title']);
        $stmt->bindParam(':task_description', $task['task_description']);
        $stmt->bindParam(':priority', $task['priority']);
        $stmt->bindParam(':due_date', $task['due_date']);
        $stmt->bindParam(':is_completed', $task['is_completed']);
        $stmt->bindParam(':ID_category', $task['ID_category']);
        $stmt->bindParam(':ID_user', $task['ID_user']);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "<p>Error adding task: " . $e->getMessage() . "</p>";
    }
}

// Check if delete action is requested
if (isset($_GET['delete'])) {
    $task_id = $_GET['delete'];
    deleteTask($con, $task_id);
}

// Check if edit action is requested
if (isset($_POST['edit'])) {
    $task = [
        'task_id' => $_POST['task_id'],
        'task_title' => $_POST['task_title'],
        'task_description' => $_POST['task_description'],
        'priority' => $_POST['priority'],
        'due_date' => $_POST['due_date'],
        'is_completed' => isset($_POST['is_completed']) ? 1 : 0,
        'ID_category' => $_POST['ID_category']
    ];
    editTask($con, $task);
}

// Check if add action is requested
if (isset($_POST['add'])) {
    // Ensure the form was submitted
    if (isset($_POST['task_title'], $_POST['task_description'], $_POST['priority'], $_POST['due_date'], $_POST['ID_category'])) {
        $task = [
            'task_title' => $_POST['task_title'],
            'task_description' => $_POST['task_description'],
            'priority' => $_POST['priority'],
            'due_date' => $_POST['due_date'],
            'is_completed' => isset($_POST['is_completed']) ? 1 : 0,
            'ID_category' => $_POST['ID_category'],
            'ID_user' => $_SESSION['ID_user']
        ];
        addTask($con, $task);
    }
}

// Check if user ID is set in session
if (!isset($_SESSION['ID_user'])) {
    echo "User ID not found.";
    exit();
}

// Retrieve user ID from session
$user_id = $_SESSION['ID_user'];

// Retrieve all tasks for the current user from the database
$sql = "SELECT * FROM tasks WHERE ID_user = :user_id";
$stmt = $con->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve all categories from the database
$sql_categories = "SELECT * FROM categories";
$stmt_categories = $con->query($sql_categories);
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
    <link href="tasks.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .bg-btn {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <?php include './header.php'; ?>
  
    <div class="fixed top-0 left-0 w-full h-full flex justify-center items-center hidden" id="overlay" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="form-container bg-white rounded-lg shadow-md p-8 w-1/2">
            <h2>Add Task</h2>
            <form action="" method="post" id="addTaskForm">
                <label class="block mb-2" for="add_task_title">Title:</label>
                <input type="text" name="task_title" id="add_task_title" required><br>
                <label class="block mb-2" for="add_task_description">Description:</label>
                <input type="text" name="task_description" id="add_task_description" required><br>
                <label class="block mb-2" for="add_priority">Priority:</label>
                <select name="priority" id="add_priority" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select><br>
                <label for="add_due_date">Due Date:</label>
                <input type="date" name="due_date" id="add_due_date" required><br>
                <label for="add_is_completed">Completed:</label>
                <input type="checkbox" name="is_completed" id="add_is_completed"><br>
                <label for="add_ID_category">Category:</label>
                <select name="ID_category" id="add_ID_category" required>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo htmlspecialchars($category['ID_category']); ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                    <?php endforeach; ?>
                </select><br>
                <input type="submit" name="add" value="Add Task" class="bg-btn">
            </form>
        </div>
    </div>

    <div class="text-right mb-4">
        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mt-20" onclick="showAddForm()">Add Task</button>
    </div>
    
    <h2 class="h2 text-2xl font-bold mb-4">All Tasks</h2>

    <!-- Tasks Table -->
    <div class="table bg-white p-4 rounded-lg shadow-md">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200">ID</th>
                    <th class="py-2 px-4 border-b border-gray-200">Title</th>
                    <th class="py-2 px-4 border-b border-gray-200">Description</th>
                    <th class="py-2 px-4 border-b border-gray-200">Priority</th>
                    <th class="py-2 px-4 border-b border-gray-200">Due Date</th>
                    <th class="py-2 px-4 border-b border-gray-200">Completed</th>
                    <th class="py-2 px-4 border-b border-gray-200">Category</th>
                    <th class="py-2 px-4 border-b border-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task) : ?>
                    <tr>
                        <td class='py-2 px-4 border-b border-gray-200'><?php echo htmlspecialchars($task['task_id']); ?></td>
                        <td class='py-2 px-4 border-b border-gray-200'><?php echo htmlspecialchars($task['task_title']); ?></td>
                        <td class='py-2 px-4 border-b border-gray-200'><?php echo htmlspecialchars($task['task_description']); ?></td>
                        <td class='py-2 px-4 border-b border-gray-200'><?php echo htmlspecialchars($task['priority']); ?></td>
                        <td class='py-2 px-4 border-b border-gray-200'><?php echo htmlspecialchars($task['due_date']); ?></td>
                        <td class='py-2 px-4 border-b border-gray-200'><?php echo $task['is_completed'] ? 'Yes' : 'No'; ?></td>
                        <td class='py-2 px-4 border-b border-gray-200'><?php echo htmlspecialchars($task['ID_category']); ?></td>
                        <td class='py-2 px-4 border-b border-gray-200'>
                            <button class='bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600' onclick='showEditForm(<?php echo json_encode($task); ?>)'>Edit</button>
                            <a href='?delete=<?php echo $task['task_id']; ?>' class='bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600' onclick='return confirm("Are you sure you want to delete this task?")'>Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Task Form -->
    <div class="fixed top-0 left-0 w-full h-full flex justify-center items-center hidden" id="editOverlay" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="form-container bg-white rounded-lg shadow-md p-8 w-1/2">
            <h2>Edit Task</h2>
            <form action="" method="post" id="editTaskForm">
                <input type="hidden" name="task_id" id="edit_task_id">
                <label class="block mb-2" for="edit_task_title">Title:</label>
                <input type="text" name="task_title" id="edit_task_title" required><br>
                <label class="block mb-2" for="edit_task_description">Description:</label>
                <input type="text" name="task_description" id="edit_task_description" required><br>
                <label class="block mb-2" for="edit_priority">Priority:</label>
                <select name="priority" id="edit_priority" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select><br>
                <label for="edit_due_date">Due Date:</label>
                <input type="date" name="due_date" id="edit_due_date" required><br>
                <label for="edit_is_completed">Completed:</label>
                <input type="checkbox" name="is_completed" id="edit_is_completed"><br>
                <label for="edit_ID_category">Category:</label>
                <select name="ID_category" id="edit_ID_category" required>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo htmlspecialchars($category['ID_category']); ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                    <?php endforeach; ?>
                </select><br>
                <input type="submit" name="edit" value="Save Changes">
                <button type="button" onclick="hideEditForm()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function showAddForm() {
            document.getElementById('overlay').style.display = 'flex';
        }

        function hideAddForm() {
            document.getElementById('overlay').style.display = 'none';
        }

        function showEditForm(task) {
            document.getElementById('edit_task_id').value = task.task_id;
            document.getElementById('edit_task_title').value = task.task_title;
            document.getElementById('edit_task_description').value = task.task_description;
            document.getElementById('edit_priority').value = task.priority;
            document.getElementById('edit_due_date').value = task.due_date;
            document.getElementById('edit_is_completed').checked = task.is_completed == 1;
            document.getElementById('edit_ID_category').value = task.ID_category;
            document.getElementById('editOverlay').style.display = 'flex';
        }

        function hideEditForm() {
            document.getElementById('editOverlay').style.display = 'none';
        }
    </script>
</body>

</html>

<?php
require_once("config.php");
require_once("select-department.php");

$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<head>
    <title>Employees Management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
        .row.content {
            height: 800px
        }

        /* Set gray background color and 100% height */
        .sidenav {
            background-color: rgba(240,240,240);
            height: 150%;
            font-size: medium;
        }

        h1,
        h4 {
            text-decoration: underline
        }

        nav {
            font-size: medium;
            /* background: #fc5369; */
        }

        thead {
            background-color: rgba(240,240,240);
            font-size: medium;
        }

        td {
            text-align: left;
            font-size: medium;
        }

        #leftAlign {
            font-size: medium;
            text-align: left;
        }

        .btn-primary {
            font-size: medium;
        }

        /* Set black background color, white text and some padding */
        footer {
            background-color: #555;
            color: white;
            padding: 15px;
        }

        /* On small screens, set height to 'auto' for sidenav and grid */
        @media screen and (max-width: 767px) {

            .sidenav {
                height: auto;
                padding: 15px;
                font-size: medium;
            }

            .row.content {
                height: auto;
            }
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/emp_mgnt.php">Employee Management System</a>
                </div>
                <ul class="nav navbar-nav">
                    <li><a href="/emp.php">View All Employees</a></li>
                    <li><a href="/emp_new.php">Insert New Employee</a></li>
                    <li><a href="/emp_mgnt.php">Update/Delete Employees</a></li>
                </ul>
            </div>
        </nav>
        <div class="row content">
            <div class="col-sm-2 sidenav">
                <h4>Shortcut</h4>
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/emp.php">View All Employees(emp.php)</a></li>
                    <li><a href="/emp_new.php">Insert New Employee(emp_new.php)</a></li>
                    <li><a href="/emp_mgnt.php">Update/Delete Employees(emp_mgnt.php)</a></li>
                </ul><br>
            </div>

            <?php

            if (isset($_POST['cmd']) && $_POST['cmd'] == 'del') {

                $emp_no = $_POST['emp_no'];
                $sql = "DELETE FROM employees WHERE emp_no = $emp_no";
                $sql2 = "DELETE FROM dept_emp WHERE emp_no = $emp_no";
                $sql3 = "DELETE FROM titles WHERE emp_no = $emp_no";
                $sql4 = "DELETE FROM salaries WHERE emp_no = $emp_no";

                if (mysqli_query($conn, $sql)) {
                    echo "Employee's record deleted successfully. \n";
                } else {
                    echo "Error deleting record: " . mysqli_connect_error();;
                }

                if (mysqli_query($conn, $sql2)) {
                    echo "Record of employee's department deleted successfully. \n";
                } else {
                    echo "Error deleting record: " . mysqli_connect_error();;
                }

                if (mysqli_query($conn, $sql3)) {
                    echo "Record of employee's title deleted successfully. \n";
                } else {
                    echo "Error deleting record: " . mysqli_connect_error();;
                }

                if (mysqli_query($conn, $sql4)) {
                    echo "Record of employee's salaries deleted successfully. \n";
                } else {
                    echo "Error deleting record: " . mysqli_connect_error();;
                }
                
            }

            if (isset($_POST['cmd']) && $_POST['cmd'] == 'save') {
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $emp_no = $_POST['emp_no'];
                $gender = $_POST['gender'];
                $birth_date = $_POST['birth_date'];
                $hire_date = $_POST['hire_date'];
                $dept_no = $_POST['dept_no'];
                $title = $_POST['title'];
                $salary = $_POST['salary'];

                // 1.Update employees table
                $sql = "UPDATE employees SET 
            emp_no = '$emp_no',
            birth_date = '$birth_date',
            first_name = '$first_name',
            last_name = '$last_name',
            gender = '$gender',
            hire_date = '$hire_date'
            WHERE emp_no = '$emp_no'";

                if ($conn->query($sql) === TRUE) {
                    echo "Success<br/>$sql<br/>";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

                // 2.Update dept_emp
                $sql = "SELECT * FROM dept_emp WHERE emp_no = '$emp_no' AND dept_no = '$dept_no' ORDER BY emp_no ASC";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {

                } else {
                    $sql = "UPDATE dept_emp SET to_date =  NOW() WHERE emp_no = '$emp_no'AND dept_no = '$dept_no'";
                    $result7 = mysqli_query($conn, $sql);
                    $row7 = mysqli_fetch_assoc($result7);
                    if ($conn->query($sql) === TRUE) {
                        echo "Success<br/>$sql<br/>";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                    $sql = "INSERT INTO dept_emp (emp_no,dept_no,from_date,to_date) VALUES ('$emp_no','$dept_no', NOW(), '9999-01-01')";
                    if ($conn->query($sql) === TRUE) {
                        echo "Success<br/>$sql<br/>";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }

                $sql = "SELECT * FROM titles WHERE emp_no = '$emp_no' AND title = '$title' ORDER BY emp_no ASC";
                $result3 = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result3) > 0) {
                   
                } else {
           
                    $sql = "UPDATE titles SET to_date = NOW() WHERE emp_no = '$emp_no' AND title = '$title' ";
                    if ($conn->query($sql) === TRUE) {
                        echo "Success<br/>$sql<br/>";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }

                    $sql = "INSERT INTO titles (emp_no,title,from_date,to_date) VALUES ('$emp_no','$title', NOW(), '9999-01-01')";
                    if ($conn->query($sql) === TRUE) {
                        echo "Success<br/>$sql<br/>";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }

                // 4.Update salaries
                $sql = "SELECT * FROM salaries WHERE emp_no = '$emp_no' AND salary = '$salary' ORDER BY emp_no ASC";
                $result5 = mysqli_query($conn, $sql);
                $row5 = mysqli_fetch_assoc($result5);
                $from_date = $row5['to_date'];

                if (mysqli_num_rows($result5) > 0) {
                
                } else {
                    
                    $sql = "UPDATE salaries SET to_date = NOW() WHERE emp_no = '$emp_no'";
                    if ($conn->query($sql) === TRUE) {
                        echo "Success<br/>$sql<br/>";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }

                    $sql = "INSERT INTO salaries (emp_no,salary,from_date,to_date) VALUES ('$emp_no','$salary', NOW(), '9999-01-01')";
                    if ($conn->query($sql) === TRUE) {
                        echo "Success<br/>$sql<br/>";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
            }
            ?>

            <div class="col-sm-10">
                <h1 style="text-indent: 50px;">Employee Management</h1><br />
                <?php

                $sql = "SELECT * from (((employees e left join dept_emp de on e.emp_no = de.emp_no)left join departments d on de.dept_no = d.dept_no) left join titles t on e.emp_no = t.emp_no)left join salaries s on e.emp_no = s.emp_no limit 10;";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                ?>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <th>Employee ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Department</th>
                            <th>Birth Date</th>
                            <th>Hire Date</th>
                            <th>Gender</th>
                            <th>Title</th>
                            <th>Salary</th>
                            <th>Delete</th>
                            <th>Update</th>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) {
                                $emp_no = $row['emp_no'];
                                $dept_name = $row['dept_name'];
                            ?>
                                <tr>
                                    <td><?php echo $row['emp_no']; ?></td>
                                    <td><?php echo $row['first_name']; ?></td>
                                    <td><?php echo $row['last_name']; ?></td>
                                    <td><?php echo $row['dept_name']; ?></td>
                                    <td><?php echo $row['birth_date']; ?></td>
                                    <td><?php echo $row['hire_date']; ?></td>
                                    <td><?php echo $row['gender']; ?></td>
                                    <td><?php echo $row['title']; ?></td>
                                    <td><?php echo $row['salary'] ?></td>
                                    <!-- <td><?php echo $row['de_from_date'] ?></td>
                                    <td><?php echo $row['de_to_date'] ?></td>
                                    <td><?php echo $row['t_from_date'] ?></td>
                                    <td><?php echo $row['t_to_date'] ?></td>
                                    <td><?php echo $row['from_date'] ?></td>
                                    <td><?php echo $row['to_date'] ?></td> -->
                                    <td>
                                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="form<?php echo $row['emp_no']; ?>">
                                            <input type="hidden" name="emp_no" value="<?php echo $row['emp_no']; ?>" />
                                            <input type="hidden" name="cmd" value="del" />
                                        </form>
                                        <!-- <button type="submit" form="form<?php echo $row['emp_no']; ?>">
                            <img src="img/del_icon.jpg" width="20">
                        </button> -->
                                        <button onClick='confirmDelete("form<?php echo $row['emp_no']; ?>", "<?php echo $row['emp_no']; ?>")'>
                                            <img src="icon-del.jpeg" width="20">
                                        </button>
                                    </td>
                                    <td>
                                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="formUpdate<?php echo $row['emp_no']; ?>">
                                            <input type="hidden" name="emp_no" value="<?php echo $row['emp_no']; ?>" />
                                            <input type="hidden" name="cmd" value="update" />

                                            <button onClick=''>
                                                <img src="icon-edit.png" width="20" />
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>

                    <?php
                    if (isset($_POST['cmd']) && $_POST['cmd'] == 'update') {
                        // Update 
                        $emp_no = $_POST['emp_no'];
                        $sql = "SELECT * FROM employees WHERE emp_no = $emp_no";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                       
                        $sql = "SELECT * FROM dept_emp WHERE emp_no = $emp_no AND to_date = '9999-01-01' ORDER BY emp_no ASC";
                        $result2 = mysqli_query($conn, $sql);
                        $row2 = mysqli_fetch_assoc($result2);
                    
                        $sql = "SELECT * FROM titles WHERE emp_no = $emp_no ORDER BY emp_no ASC";
                        $result3 = mysqli_query($conn, $sql);
                        $row3 = mysqli_fetch_assoc($result3);
                
                        $sql = "SELECT * FROM salaries WHERE emp_no = '$emp_no' AND to_date = (SELECT MAX(to_date) FROM salaries WHERE emp_no = '$emp_no')ORDER BY emp_no ASC";
                        $result4 = mysqli_query($conn, $sql);
                        $row4 = mysqli_fetch_assoc($result4);
                    }
                    ?>

                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <input type="hidden" name="cmd" value="save" />
                        <table>
                            <tr>
                                <th>Department</th>
                                <td class="dropdown-header" id="leftAlign">
                                    <?php echo select_department($conn, $row2['dept_no']); ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Emp#</th>
                                <td><input type="text" name="emp_no" class="form-control" value="<?php echo $row['emp_no']; ?>"></td>
                            </tr>
                            <tr>
                                <th>First Name</th>
                                <td><input type="text" name="first_name" class="form-control" value="<?php echo $row['first_name']; ?>"></td>
                            </tr>
                            <tr>
                                <th>Last Name</th>
                                <td><input type="text" name="last_name" class="form-control" value="<?php echo $row['last_name']; ?>"></td>
                            </tr>
                            <tr>
                                <th>Birth Date</th>
                                <td><input type="date" name="birth_date" class="form-control" value="<?php echo $row['birth_date']; ?>"></td>
                            </tr>
                            <tr>
                                <th>Hire Date</th>
                                <td><input type="date" name="hire_date" class="form-control" value="<?php echo $row['hire_date']; ?>"></td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td class="checkbox" id="leftAlign">
                                <input type="radio" name="gender" value="M">  Male<br/>
                                <input type="radio" name="gender" value="F">  Female</td>
                            </tr>
                            <tr>
                                <th>Title</th>
                                <td><input type="text" name="title" class="form-control" value="<?php echo $row3['title']; ?>"></td>
                            </tr>
                            <tr>
                                <th>Salary</th>
                                <td><input type="number" name="salary" class="form-control" value="<?php echo $row4['salary']; ?>"></td>
                            </tr>
                        </table>
                        <br />
                        <input class="btn btn-primary" type="submit" value="UPDATE" />
                    </form>

                <?php
                }
                mysqli_close($conn);
                ?>
            </div>
        </div>
    </div>

    <footer class="container-fluid" id="leftAlign">
    </footer>

</body>

<script>
    function confirmDelete(formId, empName) {
        // to type this `, hold ALT and then type 96
        if (confirm(`Are you sure to delete ${empName}?`)) {
            // go on an delete
            console.log("DELETE")
            document.getElementById(formId).submit()
        }
    }
</script>
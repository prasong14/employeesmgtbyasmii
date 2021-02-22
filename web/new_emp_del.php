<style>
.box {
  border: solid 1px black
}
</style>

<?php
require_once("config.php");

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
?>

<?php
//Process previous request
if (isset($_POST['cmd']) && $_POST['cmd'] == 'del') {
  // Delete employee
  $emp_no = $_POST['emp_no'];
  $sql = "DELETE FROM employees WHERE emp_no = $emp_no";
  if (mysqli_query($conn, $sql)) {
    echo "Record deleted successfully";
  } else {
    echo "Error deleting record: " . mysqli_connect_error();
  }
}
?>


<h3>Employee Management</h3>
<?php
$sql = "SELECT * FROM employees LIMIT 10";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
?>
  <table>
    <tbody>
      <?php
  // output data of each row
  while($row = mysqli_fetch_assoc($result)) {
    ?>
      <tr>
        <td class = "box"><?php echo $row['first_name']; ?></td>
        <td class = "box"><?php echo $row['last_name']; ?></td>
        <td class = "box"><?php echo $row['birth_date']; ?></td>
        <td class = "box"><?php echo $row['hire_date']; ?></td>
        <td class = "box"><?php echo $row['gender']; ?></td>
        <td>
         <form method = "POST" action = "<?php echo $_SERVER['PHP_SELF']; ?>" id = "form<?php echo $row['emp_no']; ?>">
            <input type = "hidden" name = "emo_np" value = "<?php echo $row[`emp_no`]; ?>"/>
            <input type = "hidden" name = "cmd" value = "del"/>
         </form>
          <button onclick = 'confirmDelete("form<?php echo $row[`emp_no`]; ?>","<?php echo $row[`first_name`]; ?>" )'>
            <img src = "img/icon-del.jpeg" width = "20" />
          </button>
        </td>
      </tr>

    <?php
  }
  ?>
  </tbody>
 </table>
<?php
}

mysqli_close($conn);
?>
<script>
function confirmDelete(formId, empName){
  if (confirm(`Are you sure to delete ${smpName}?")) {
    console.log("Delete")
    document.getElementById(formId).submit()
  }
}
</script>
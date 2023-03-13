<?php
$dbhost = "crpl.cis.udel.edu";
$dbuser = "oaccvv";
$dbpass = "openaccresults";
$db = "OpenACC";

// Get the value of the 'id' parameter
$compiler_results_html = $_GET['compiler_results'];
$runtime_results_html = $_GET['runtime_results'];
$compiler_html = $_GET['compiler_name'];
$language_html = $_GET['language'];
$system_html = $_GET['test_system'];
// Create connection
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
//$sql = "SELECT * FROM Results WHERE compiler_result = ? AND runtime_result = ? AND compiler_name = ?";
$sql = "SELECT * FROM Results WHERE ";
if ($language_html == "Both") {
    $sql .= "(test_name LIKE '%.c' OR test_name LIKE '%.F90') ";
} else {
    $sql .= "test_name LIKE '%.$language_html' ";
}

if ($compiler_html == "All") {
    $sql .= "AND (compiler_name = 'Cray' OR compiler_name = 'nvc' OR compiler_name = 'Clacc' OR compiler_name = 'GCC') ";
} else {
    $sql .= "AND compiler_name = '$compiler_html' ";
}

if ($compiler_results_html == "Both") {
    $sql .= "AND (compiler_result = 'Pass' OR compiler_result = 'Fail') ";
} else {
    $sql .= "AND compiler_result = '$compiler_results_html' ";
}

if ($runtime_results_html == "Both") {
   $sql .= "AND (runtime_result = 'Pass' OR runtime_result = 'Fail') ";
} else {
   $sql .= "AND runtime_result = '$runtime_results_html' ";
}
if ($system_html == "All") {
   $sql .= "AND (system_name = 'Perlmutter' OR system_name = 'DARWIN' OR system_name = 'Summit' OR system_name = 'Spock' OR system_name = 'Jetstream');";
} else {
   $sql .= "AND system_name = '$system_html'; ";
}
echo $sql;
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Error in preparing statement: " . mysqli_error($conn);
    exit();
}

$stmt->execute();

$result = $stmt->get_result();


if (!$result) {
    echo "Error executing query: " . $conn->error;
    exit;
}

function printhtml($no, $demono, $test_name, $compiler, $system, $compilerresult, $runtimeresult, $command, $errors, $output, $compilation_runtime, $source_code) {
    echo '<tr data-toggle="collapse" data-target="' . "#" . $demono . '" class="accordion-toggle">
<td>' . $no . '</td>
 <td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span></button></td>
              <td>' . $test_name . '</td>
              <td>' . $system . '</td>
              <td>' . $compiler . '</td>
              <td>' . $compilerresult . '</td>
              <td>' . $runtimeresult . '</td>
</tr>

        <tr>
            <td colspan="12" class="hiddenRow" style="padding: 0 !important;">
              <div class="accordian-body collapse" id="' . $demono . '">
              <table class="table table-striped">


              <tbody>

<tr>
               <td> Compilation Command </td>
               <td> ' . $command . ' </td>
               </tr>
            <tr>
           <td> Compilation Error </td>
           <td>' . $errors . '</td>
           </tr>
 <tr>
                 <td> Compilation Runtime </td>
                 <td>' . $compilation_runtime . '</td>
                 </tr>
           <tr>
              <td> Segment Contents </td>
              <td>' . $source_code . '</td>
              </tr>

                    <tr>

              </div>
          </td>
        </tr>
                      </tbody>
                </table>

              </div>
          </td>
        </tr>

';

}


$number = 1;
while ($row = $result->fetch_assoc()) {
    $demoid = "demo" . $number;
    $test_name = $row['test_name'];
    $system = $row['system_name'];
    $compiler = $row['compiler_name'];
    $compilerresult = $row['compiler_result'];
    $runtimeresult = $row['runtime_result'];
    $command = $row['command'];
    $errors = $row['errors'];
    $output = $row['output'];
    $compilation_runtime = $row['runtime'];
    $source_code = $row['segment_contents'];
    printhtml($number, $demoid, $test_name, $compiler, $system, $compilerresult, $runtimeresult, $command, $errors, $output, $compilation_runtime, $source_code);
   $number++;
}
$conn->close();

?>
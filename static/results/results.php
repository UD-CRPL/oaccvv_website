<?php
$dbhost = "127.0.0.1";
$dbuser = "oaccvv";
$dbpass = "openaccresults";
$db = "OpenACC";

// Get the value of the 'id' parameter
$tab = $_GET['tab'];
$test_name_html = $_GET['name'];
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

// if (empty($compiler_results_html) || empty($runtime_results_html) || empty($compiler_html) || empty($language_html) || empty($system_html)) {
    // One or more variables are empty, so don't execute the SQL query
    // $sql = "SELECT * FROM Results ORDER BY test_name ASC";
// } else {
    // All variables are non-empty, so execute the SQL query
    $sql = "SELECT * FROM Results WHERE ";
    if ($test_name_html == "") {
        $sql .= "test_name LIKE '%' ";
    } else {
        $sql .= "test_name LIKE '%$test_name_html%' ";
    }

    if ($language_html == "Both") {
        $sql .= "AND (test_name LIKE '%.c' OR test_name LIKE '%.F90') ";
    } else {
        $sql .= "AND test_name LIKE '%.$language_html' ";
    }

    if ($compiler_html == "All") {
        $sql .= "AND (compiler_name LIKE 'Cray%' OR compiler_name LIKE 'nvc%' OR compiler_name LIKE 'Clacc%' OR compiler_name LIKE 'GCC%') ";
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
    $sql .= "AND (system_name = 'Perlmutter' OR system_name = 'DARWIN' OR system_name = 'Summit' OR system_name = 'Crusher') ORDER BY test_name ASC;";
    } else {
    $sql .= "AND system_name LIKE '$system_html'; ";
    }// Execute the SQL query here
// }

// echo $sql;
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

function results_html($no, $demono, $test_name, $compiler, $system, $compilerresult, $runtimeresult, $command, $errors, $output, $compilation_runtime, $source_code) {
    // Add color styling for compiler and runtime results
    $compilerresult_styled = $compilerresult == "Pass" ? 
        '<span style="color: green; font-weight: bold;">' . $compilerresult . '</span>' : 
        '<span style="color: red; font-weight: bold;">' . $compilerresult . '</span>';
    
    $runtimeresult_styled = $runtimeresult == "Pass" ? 
        '<span style="color: green; font-weight: bold;">' . $runtimeresult . '</span>' : 
        '<span style="color: red; font-weight: bold;">' . $runtimeresult . '</span>';

    echo '<tr data-toggle="collapse" data-target="' . "#" . $demono . '" class="accordion-toggle">
<td>' . $no . '</td>
 <td><button class="btn btn-default btn-xs"><span class="ti-split-v-alt"></span></button></td>
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
              <td><a href="' . $source_code . '" target="_blank">' . $source_code . '</td>
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

function summary_html($my_dict) {
//     echo '<tr data-toggle="collapse" class="accordion-toggle">
//             <td>' . $no . '</td>
//             <td>' . $test_name . '</td>
//             <td>' . $system . '</td>
//             <td>' . $compiler . '</td>
//             <td>' . $compilerresult . '</td>
//             <td>' . $runtimeresult . '</td>
// </tr>';
$compilers = array('nvc 23_1', 'GCC 12_2', 'Cray 15_0_0', 'Clacc #4879e9');

// generate the HTML table header
// echo '<div class="tab-pane active" id="summary" role="tabpanel">
//         <table data-toggle="table">
//             <thead>
//                 <tr><table><tr><th>#</th><th>Test Name</th><th>System Name</th>';
//                 foreach ($compilers as $compiler) {
//                     echo "<th>{$compiler} CR</th><th>{$compiler} RR</th>";
//                 }
//         echo "</tr></thead>";

// initialize a counter for the serial number column
$serial_number = 1;
// iterate through the $my_dict array and populate the table rows
foreach ($my_dict as $test_name => $system_data) {
    foreach ($system_data as $system_name => $compiler_data) {
        // initialize arrays to store the results for each compiler
        $compiler_results = array();
        $runtime_results = array();
        
        // extract the results for each compiler and store in the arrays
        foreach ($compilers as $compiler) {
            if (isset($compiler_data[$compiler])) {
                $compiler_results[$compiler] = $compiler_data[$compiler]['compiler_result'];
                $runtime_results[$compiler] = $compiler_data[$compiler]['runtime_result'];
            } else {
                $compiler_results[$compiler] = '-';
                $runtime_results[$compiler] = '-';
            }
        }
        
        // insert the data into HTML table cells using the echo statement
        echo "<tr><td>{$serial_number}</td><td>{$test_name}</td><td>{$system_name}</td>";
        foreach ($compilers as $compiler) {
            echo "<td>{$compiler_results[$compiler]}</td><td>{$runtime_results[$compiler]}</td>";
        }
        echo "</tr>";
        
        // increment the serial number counter
        $serial_number++;
    }
}

// close the HTML table
// ec
}

if ($tab == "summary") {
    // One or more variables are empty, so don't execute the SQL query
    $number = 1;
    $my_dict = array();
    while ($row = $result->fetch_assoc()) {
        $test_name = $row['test_name'];
        $system = $row['system_name'];
        $compiler = $row['compiler_name'];
        $compilerresult = $row['compiler_result'];
        $runtimeresult = $row['runtime_result'];
        
        if (!isset($my_dict[$test_name])) {
            $my_dict[$test_name] = array();
        }
        
        if (!isset($my_dict[$test_name][$system])) {
            $my_dict[$test_name][$system] = array();
        }
        
        $my_dict[$test_name][$system][$compiler] = array(
            'compiler_result' => $compilerresult,
            'runtime_result' => $runtimeresult
        );
        // print_r($my_dict[$test_name][$system]);
        $number++;
    }
    
    summary_html($my_dict);
} elseif($tab == "results") {
    $number = 1;
    while ($row = $result->fetch_assoc()) {
        if ($number > 1000) {
            break; // break out of the loop once we have reached the maximum number of iterations
        }
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
        results_html($number, $demoid, $test_name, $compiler, $system, $compilerresult, $runtimeresult, $command, $errors, $output, $compilation_runtime, $source_code);
    $number++;
    }
}
$conn->close();

?>
<?php
$dbhost = "127.0.0.1";
$dbuser = "oaccvv";
$dbpass = "openaccresults";
$db = "OpenACC";

// Create connection first
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);

// Check connection
if (!$conn) {
    die('<tr><td colspan="7" class="text-center text-danger">Connection failed: ' . mysqli_connect_error() . '</td></tr>');
}

// Sanitize input parameters (only after connection is established)
function sanitize($conn, $param) {
    return $param === null ? null : mysqli_real_escape_string($conn, $param);
}

// Get and sanitize parameters
$tab = sanitize($conn, $_GET['tab'] ?? 'summary');
$test_name_html = sanitize($conn, $_GET['name'] ?? '');
$compiler_results_html = sanitize($conn, $_GET['compiler_results'] ?? 'Both');
$runtime_results_html = sanitize($conn, $_GET['runtime_results'] ?? 'Both');
$compiler_html = sanitize($conn, $_GET['compiler_name'] ?? 'All');
$language_html = sanitize($conn, $_GET['language'] ?? 'Both');
$system_html = sanitize($conn, $_GET['test_system'] ?? 'All');

// Build SQL query with prepared statement placeholders
$sql = "SELECT * FROM Results WHERE ";

// Test name filter
$sql .= $test_name_html ? "test_name LIKE ? " : "test_name LIKE '%' ";

// Language filter
if ($language_html == "Both") {
    $sql .= "AND (test_name LIKE '%.c' OR test_name LIKE '%.F90') ";
} else {
    $sql .= "AND test_name LIKE '%.$language_html' ";
}

// Compiler filter
if ($compiler_html == "All") {
    $sql .= "AND (compiler_name LIKE 'Cray%' OR compiler_name LIKE 'nvc%' OR compiler_name LIKE 'Clacc%' OR compiler_name LIKE 'GCC%') ";
} else {
    $sql .= "AND compiler_name = ? ";
}

// Compiler result filter
if ($compiler_results_html == "Both") {
    $sql .= "AND (compiler_result = 'Pass' OR compiler_result = 'Fail') ";
} else {
    $sql .= "AND compiler_result = ? ";
}

// Runtime result filter
if ($runtime_results_html == "Both") {
    $sql .= "AND (runtime_result = 'Pass' OR runtime_result = 'Fail') ";
} else {
    $sql .= "AND runtime_result = ? ";
}

// System filter
if ($system_html == "All") {
    $sql .= "AND (system_name = 'Perlmutter' OR system_name = 'Frontier' OR system_name = 'DARWIN' OR system_name = 'Summit' OR system_name = 'Crusher') ";
} else {
    $sql .= "AND system_name = ? ";
}

$sql .= "ORDER BY test_name ASC";

// Debug SQL
// echo "<!-- SQL: $sql -->";

// Prepare statement
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo '<tr><td colspan="7" class="text-center text-danger">Error preparing statement: ' . mysqli_error($conn) . '</td></tr>';
    exit();
}

// Bind parameters
$types = '';
$params = [];

if ($test_name_html) {
    $types .= 's';
    $search_param = '%' . $test_name_html . '%';
    $params[] = &$search_param;
}

if ($compiler_html != "All") {
    $types .= 's';
    $params[] = &$compiler_html;
}

if ($compiler_results_html != "Both") {
    $types .= 's';
    $params[] = &$compiler_results_html;
}

if ($runtime_results_html != "Both") {
    $types .= 's';
    $params[] = &$runtime_results_html;
}

if ($system_html != "All") {
    $types .= 's';
    $params[] = &$system_html;
}

if (!empty($params)) {
    array_unshift($params, $types);
    call_user_func_array([$stmt, 'bind_param'], $params);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo '<tr><td colspan="7" class="text-center text-danger">Error executing query: ' . $conn->error . '</td></tr>';
    exit;
}

// Check if any results were found
if ($result->num_rows == 0) {
    echo '<tr><td colspan="7" class="text-center">No matching results found</td></tr>';
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

    echo '<tr data-toggle="collapse" data-target="#' . $demono . '" class="accordion-toggle">
    <td>' . $no . '</td>
    <td><button class="btn btn-default btn-xs"><span class="ti-split-v-alt"></span></button></td>
    <td>' . htmlspecialchars($test_name) . '</td>
    <td>' . htmlspecialchars($system) . '</td>
    <td>' . htmlspecialchars($compiler) . '</td>
    <td>' . $compilerresult_styled . '</td>
    <td>' . $runtimeresult_styled . '</td>
    </tr>
    <tr>
        <td colspan="12" class="hiddenRow" style="padding: 0 !important;">
            <div class="accordian-body collapse" id="' . $demono . '">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td>Compilation Command</td>
                            <td>' . htmlspecialchars($command) . '</td>
                        </tr>
                        <tr>
                            <td>Compilation Error</td>
                            <td>' . htmlspecialchars($errors) . '</td>
                        </tr>
                        <tr>
                            <td>Compilation Runtime</td>
                            <td>' . htmlspecialchars($compilation_runtime) . '</td>
                        </tr>
                        <tr>
                            <td>Segment Contents</td>
                            <td><a href="' . htmlspecialchars($source_code) . '" target="_blank">' . htmlspecialchars($source_code) . '</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </td>
    </tr>';
}

function summary_html($my_dict) {
    // Check if there's data to display
    if (empty($my_dict)) {
        echo '<tr><td colspan="7" class="text-center">No matching results found</td></tr>';
        return;
    }

    $compilers = array('nvc 24_5', 'Cray 19_0_0', 'GCC 12_2' , 'Clacc #4879e9');
    $serial_number = 1;
    
    foreach ($my_dict as $test_name => $system_data) {
        foreach ($system_data as $system_name => $compiler_data) {
            $combined_results = array();
            
            foreach ($compilers as $compiler) {
                if (isset($compiler_data[$compiler])) {
                    $compiler_result = $compiler_data[$compiler]['compiler_result'];
                    $runtime_result = $compiler_data[$compiler]['runtime_result'];
                    
                    if ($compiler_result == "Pass" && $runtime_result == "Pass") {
                        $combined_results[$compiler] = '<span style="color: green; font-weight: bold;">Pass</span>';
                    } else {
                        $combined_results[$compiler] = '<span style="color: red; font-weight: bold;">Fail</span>';
                    }
                } else {
                    $combined_results[$compiler] = '<span class="text-muted">-</span>';
                }
            }
            
            echo '<tr>',
                 '<td>', $serial_number, '</td>',
                 '<td>', htmlspecialchars($test_name), '</td>',
                 '<td>', htmlspecialchars($system_name), '</td>';
                 
            foreach ($compilers as $compiler) {
                echo '<td>', $combined_results[$compiler], '</td>';
            }
            
            echo '</tr>';
            $serial_number++;
        }
    }
}

// Process results based on tab
if ($tab == "summary") {
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
    }
    
    summary_html($my_dict);
} elseif($tab == "results") {
    $number = 1;
    $limit = 1000; // Reasonable limit
    while ($row = $result->fetch_assoc()) {
        if ($number > $limit) {
            echo '<tr><td colspan="7" class="text-center text-warning">Showing first ' . $limit . ' results. Please refine your search criteria.</td></tr>';
            break;
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

// Close connection
$conn->close();
?>
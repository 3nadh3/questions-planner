<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Questions planned</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet'href='Questions planned.css'> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css">

</head>
<body>
    

<?php
$start = $_POST["startNumber"];
$end = $_POST["endNumber"];
$totalQuestions = $_POST["totalQuestions"];
$eachQuestion = $_POST["eachQuestion"];
$examRequired = $_POST["examRequired"];
$numExamQuestions = $_POST["numExamQuestions"];

function randomizer($start, $end, $totalQuestions, $eachQuestion, $examRequired, $numExamQuestions) {
    $result = array();
    $prevQuestions = array();

    for ($i = $start; $i <= $end; $i++) {
        $numbers = array();

        // Generate a unique set of random numbers for each student
        while (count($numbers) < $eachQuestion) {
            $randNum = rand(1, $totalQuestions);
            if (!in_array($randNum, $numbers) && !in_array($randNum, $prevQuestions)) {
                $numbers[] = $randNum;
            }
        }

        sort($numbers);
        $prevQuestions = $numbers;

        // If exam is required, select random questions from the set of generated questions
        if ($examRequired == "yes") {
            $examQuestions = array();

            while (count($examQuestions) < $numExamQuestions) {
                $randIndex = array_rand($numbers);
                if (!in_array($randIndex, $examQuestions)) {
                    // Check for consecutive duplicates in the examQuestions array
                    if (count($examQuestions) > 0 && abs($randIndex - end($examQuestions)) == 1) {
                        continue;
                    }
                    $examQuestions[] = $randIndex;
                }
            }

            sort($examQuestions);
            $examQuestions = array_map(function($index) use ($numbers) {
                return $numbers[$index];
            }, $examQuestions);

            $result[] = array(
                "roll" => $i,
                "questions" => $numbers,
                "examQuestions" => $examQuestions
            );
        } else {
            $result[] = array(
                "roll" => $i,
                "questions" => $numbers
            );
        }
    }

    return $result;
}


$result = randomizer($start, $end, $totalQuestions, $eachQuestion, $examRequired, $numExamQuestions);
?>
<table >
    <tr>
        <td>
            <section>
                <div class="printQuestions">
                    <table>
                        <tr>
                            <th>Roll No.</th>
                            <th>Questions</th>
                        </tr>
                        <?php
                        foreach ($result as $row) {
                            echo "<tr>";
                            echo "<td>" . $row['roll'] . "</td>";
                            echo "<td>" . implode(", ", $row['questions']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </section>
        </td>
        <?php
        if ($examRequired == "yes") {
            echo "<td>";
            ?>
            <section>
                <div class="printExamQuestions">
                    <?php
            echo "<table>";
            echo "<tr><th>Roll No.</th><th>Exam Questions</th></tr>";
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>" . $row['roll'] . "</td>";
                if (!empty($row['examQuestions'])) {
                    echo "<td>" . implode(", ", $row['examQuestions']) . "</td>";
                } else {
                    echo "<td>N/A</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "</td>";
            ?>
            </div>
            </section>

            <button onclick="printExamQuestionsTable()" class="printExamQuestionsTableButton btn btn-primary btn-sm">Print Exam Questions </button>
   
         <?php
        }
        ?>
    </tr>
</table>

<button onclick="printQuestionsTable()" class="printQuestionsTableButton btn btn-primary btn-sm">Print Questions </button>

<script>
function printQuestionsTable() {
  var table = document.querySelector('.printQuestions table');
  var win = window.open('', '', 'height=700,width=700');
  win.document.write('<html><head><title>Questions Table</title>');
  win.document.write('<style>table { border-collapse: collapse; } th, td { border: 1px solid black; padding: 5px; }</style>');
  win.document.write('</head><body>');
  win.document.write(table.outerHTML);
  win.document.write('</body></html>');
  win.print();
  win.close();
}


function printExamQuestionsTable() {
  var table = document.querySelector('.printExamQuestions table');
  var win = window.open('', '', 'height=700,width=700');
  win.document.write('<html><head><title>Exam Questions Table</title>');
  win.document.write('<style>table { border-collapse: collapse; } th, td { border: 1px solid black; padding: 5px; }</style>');
  win.document.write('</head><body>');
  win.document.write(table.outerHTML);
  win.document.write('</body></html>');
  win.print();
  win.close();
}


</script>
</body>
</html>
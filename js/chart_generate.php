<script>
    let scheme = "dark1";
    window.onload = function() {

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            // exportEnabled: true,
            theme: scheme, // "light1", "light2", "dark1", "dark2"
            // theme: "light2", // "light1", "light2", "dark1", "dark2"
            // theme: "dark1", // "light1", "light2", "dark1", "dark2"
            // theme: "dark2", // "light1", "light2", "dark1", "dark2"
            backgroundColor: "transparent",
            title: {
                text: "Attendance"
            },
            axisY: {
                title: "Attendance Count (in days)",
                includeZero: true
            },
            data: [{
                type: "column", //change type to bar, line, area, pie, etc
                //indexLabel: "{y}", //Shows y value on all Data Points
                // indexLabelFontColor: "#5A5757",
                indexLabelFontColor: "#5A5757",
                indexLabelPlacement: "outside",
                bevelEnabled: true,
                dataPoints: <?php echo json_encode($attendanceDetails, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

    }

    function generateChart() {

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            // exportEnabled: true,
            theme: scheme, // "light1", "light2", "dark1", "dark2"
            // theme: "light2", // "light1", "light2", "dark1", "dark2"
            // theme: "dark1", // "light1", "light2", "dark1", "dark2"
            // theme: "dark2", // "light1", "light2", "dark1", "dark2"
            backgroundColor: "transparent",
            title: {
                text: "Attendance"
            },
            axisY: {
                title: "Attendance Count (in days)",
                includeZero: true
            },
            data: [{
                type: "column", //change type to bar, line, area, pie, etc
                //indexLabel: "{y}", //Shows y value on all Data Points
                // indexLabelFontColor: "#5A5757",
                indexLabelFontColor: "#5A5757",
                indexLabelPlacement: "outside",
                bevelEnabled: true,
                dataPoints: <?php echo json_encode($attendanceDetails, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

    }
</script>
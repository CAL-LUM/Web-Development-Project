$(document).ready(function () {
    // Function to fetch registered bikes and create tiles
    function fetchAndDisplayBikes() {
        $.ajax({
            url: '../php/getregisteredbikes.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                // Iterate over each bike and create a tile
                data.forEach(function (bike) {
                    var bikeTile = '<div class="bike-tile">' +
                        '<img src="' + bike.imagePath + '" alt="Bike Image">' + // Updated line to display image
                        '<p>MPN: ' + bike.mpn + '</p>' +
                        '<p>Brand: ' + bike.brand + '</p>' +
                        '<p>Model: ' + bike.model + '</p>' +
                        '<p>Type: ' + bike.type + '</p>' +
                        '<p>Wheel Size: ' + bike.wheelSize + '</p>' +
                        '<p>Colour: ' + bike.colour + '</p>' +
                        '<p>Gears: ' + bike.gears + '</p>' +
                        '<p>Brake Type: ' + bike.brakeType + '</p>' +
                        '<p>Suspension: ' + bike.suspension + '</p>' +
                        '<p>Gender: ' + bike.gender + '</p>' +
                        '<p>Age Group: ' + bike.ageGroup + '</p>' +
                        '</div>';

                    $('.info-container').append(bikeTile);
                });
            },
            error: function (xhr, status, error) {
                console.error('Error fetching registered bikes:', error);
            }
        });
    }

    // Call the function to fetch and display bikes on page load
    fetchAndDisplayBikes();
});
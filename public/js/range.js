    //fisplay sliders value in the form
    var slider = document.getElementById("min_value");
    var output = document.getElementById("min_value_text");
    output.innerHTML = slider.value + ' %'; // Display the default slider value

    // Update the current slider value (each time you drag the slider handle)
    slider.oninput = function() {
        output.innerHTML = this.value + ' %';
    }
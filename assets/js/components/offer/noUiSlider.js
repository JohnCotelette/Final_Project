import noUiSlider from "nouislider";

let slider = document.getElementById('slider');

let defaultValue = 0;

if (document.getElementById("offer_salary").value !== "A négocier") {
    let string = document.getElementById("offer_salary").value;
    defaultValue = string.match(/\d+/);
}

console.log(defaultValue);

noUiSlider.create(slider, {
    start: [defaultValue],
    step: 500,
    range: {
        'min': [0],
        'max': [120000]
    },
});

let content = " brut annuel";
let nullContent = "A négocier";

let sliderValueElement = document.getElementById('sliderValue');

slider.noUiSlider.on('update', function (values, handle) {
    if (values > 0) {
        sliderValueElement.innerHTML = Math.round(values[handle]) + content;
        sliderValueElement.style.color = "inherit";
    }
    else {
        sliderValueElement.innerHTML = nullContent;
        sliderValueElement.style.color = "red";
    }

    document.getElementById("offer_salary").value = Math.round(values[handle]);
});
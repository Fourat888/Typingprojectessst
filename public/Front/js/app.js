var input = document.querySelector('.input_text');
var main = document.querySelector('#name');
var temp = document.querySelector('.temp');
var desc = document.querySelector('.desc');
var clouds = document.querySelector('.clouds');
var button= document.querySelector('.submit');

const img = document.querySelector('#weatherIcon');

button.addEventListener('click', function(name){
    fetch('https://api.openweathermap.org/data/2.5/weather?q='+input.value+'&appid=16b1873586f33cce391c0852c2679b1a&units=metric&lang=Fr')
        .then(response => response.json())
        .then(data => {
            var tempValue = data['main']['temp'];
            var nameValue = data['name'];
            var descValue = data['weather'][0]['description'];
            let icon = data['weather'][0]['icon'];

            main.innerHTML = nameValue;
            desc.innerHTML = descValue;
            temp.innerHTML = tempValue+'°C';
            img.setAttribute('src', `http://openweathermap.org/img/wn/${icon}@2x.png`);

            input.value ="";

        })

        .catch(err => alert("Wrong city name!"));
})

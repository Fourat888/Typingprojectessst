const RANDOM_QUOTE_API_URL = 'http://api.quotable.io/random'
const quoteDisplayElement = document.getElementById('quoteDisplay')
const quoteInputElement = document.getElementById('quoteInput')
const timerElement = document.getElementById('timer')
const iterator = document.getElementById('it')
const urlout2 = document.querySelector('#urlout2');
const normaltest = document.querySelector('#normaltest');
const secInputtest = parseInt(localStorage.getItem("ttime"));

let i=0

quoteInputElement.addEventListener('input', () => {
  const arrayQuote = quoteDisplayElement.querySelectorAll('span')
  const arrayValue = quoteInputElement.value.split('')
  let correct = true
  arrayQuote.forEach((characterSpan, index) => {


    const character = arrayValue[index]
    if (character == null) {
      characterSpan.classList.remove('correct')
      characterSpan.classList.remove('incorrect')
      correct = false
    } else if (character === characterSpan.innerText) {
      characterSpan.classList.add('correct')
      characterSpan.classList.remove('incorrect')
    } else {
      characterSpan.classList.remove('correct')
      characterSpan.classList.add('incorrect')
      correct = false
    }
  })

  if (correct)
  {
    i++
    renderNewQuote()
  }



})

function getRandomQuote() {
  return fetch(RANDOM_QUOTE_API_URL)
      .then(response => response.json())
      .then(data => data.content)
}

function init()
{

  if ((secInputtest)!==3) {
    startTimer()
    normaltest.innerHTML= "Obtenir 5 scores en moins de 60 secondes";

  }
  else
    normaltest.innerHTML= "Obtenir 5 scores ou plus pour avancer";




  renderNewQuote()

}
async function renderNewQuote() {
  var k1= ' <a href="';
  var k2= '">Sauvegarder avec '+i+' fois </a';
  var k22= '">Sauvegarder </a';

  var k3= '>';
  var k4 ='1';
  //url = url.replace(t1, y);
  if (i>=5 && secInputtest===3) {
    var url1 = k1 + ' addnormalgame/' + i + '/' + 0 + '/normal/' + k4 + k2 + k3;
urlout2.innerHTML=url1;

  }
  else if (i===2 && secInputtest!=3 && getTimerTime() <= 10)
  {

    normaltest.innerHTML='Bravo vous avez obtenu le level 2 en '+getTimerTime()+' secondes';
    var url2=  k1 + ' addnormalgame/' + i + '/' + getTimerTime() + '/normal/' + '2' + k22 + k3;
    urlout2.innerHTML=url2;

  }
else if (i===2 && secInputtest!=3 && getTimerTime() > 10  )
  {

    normaltest.innerHTML='Vous avez perdu avec '+(getTimerTime()-10)+' seconde(s) manquantes, vous pouvez continuez a jouer ou recommencer';
  }


  iterator.innerText = i

  const quote = await getRandomQuote()
  quoteDisplayElement.innerHTML = ''
  quote.split('').forEach(character => {
    const characterSpan = document.createElement('span')
    characterSpan.innerText = character
    quoteDisplayElement.appendChild(characterSpan)
  })
  quoteInputElement.value = null

}

let startTime
function startTimer() {
  timerElement.innerText = 0
  startTime = new Date()
  setInterval(() => {
    timer.innerText = getTimerTime()
  }, 1000)
}

function getTimerTime() {
  return Math.floor((new Date() - startTime) / 1000)
}

init()
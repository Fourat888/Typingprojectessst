const RANDOM_QUOTE_API_URL = 'http://api.quotable.io/random'
const quoteDisplayElement = document.getElementById('quoteDisplay')
const quoteInputElement = document.getElementById('quoteInput')
const timerElement = document.getElementById('timer')
const iterator = document.getElementById('it')
const urlout2 = document.querySelector('#urlout2');
const normaltest = document.querySelector('#normaltest');
const secInputtest = parseInt(localStorage.getItem("ttime"));
let i=0;
let time=1;
let total=0;
let totalplayed=0;
let isPlaying;
let o=0;
let p=0;
let l=0;
let val=0;
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
    // totalplayed+=time;
    // alert(totalplayed)
    totalplayed+=val-time;
    i++
    renderNewQuote()
    isPlaying= true;
  }



})

function getRandomQuote() {
  return fetch(RANDOM_QUOTE_API_URL)
      .then(response => response.json())
      .then(data => data.content)
}

function init()
{
  //lengthfn()
  if ((secInputtest)!==3) {

    setInterval(countdown, 1000);
    // Check game status
    setInterval(checkStatus, 500);
  }


  renderNewQuote()

}

async function lengthfn()
{
  const jsondata = await getRandomQuote();
  let val2=jsondata.length;
  let val=  Math.round(val2/2);

  alert(val)
}
async function renderNewQuote() {

  const quote = await getRandomQuote()
  let val2=quote.length;
  val=  Math.round(val2/2);

  // if (i>0)
  // {
  //   totalplayed=val-time;
  //   alert(totalplayed)
  // }

  quoteDisplayElement.innerHTML = ''
  quote.split('').forEach(character => {
    const characterSpan = document.createElement('span')
    characterSpan.innerText = character
    quoteDisplayElement.appendChild(characterSpan)
  })
  quoteInputElement.value = null
  if ((secInputtest)!==3) {
    total+=val;
time=val;
normaltest.innerHTML= "ecrire cette paragraphe en de"+val+"secondes ";
  }
  else
    normaltest.innerHTML= "Obtenir 5 scores ou plus pour avancer";
  var k1= ' <a href="';
  var k2= '">Sauvegarder avec '+i+' fois </a';
  var k22= '">Sauvegarder </a';

  var k3= '>';
  var k4 ='1';
  //url = url.replace(t1, y);
  if (i>=5 && secInputtest===3) {
    var url1 = k1 + ' addnormalgame/' + i + '/' + 0 +'/' + 0 + '/normal/' + k4 + k2 + k3;
urlout2.innerHTML=url1;

  }
  else if (i===8 && secInputtest!=3)
  {

    normaltest.innerHTML='Bravo vous avez obtenu le level 2 de '+total+'secondes en '+totalplayed+'secondes';
    var url2=  k1 + ' addnormalgame/' + i + '/'+totalplayed + '/' + total + '/normal/' + '2' + k22 + k3;
    urlout2.innerHTML=url2;
isPlaying = false;
time=-2;
  }
// else if ( i===0 &&secInputtest!=3 && getTimerTime() > (val)  )
//   {
//     normaltest.innerHTML='Vous avez perdu , vous pouvez continuez a jouer ou recommencer';
//   }


  iterator.innerText = i



}
//  async function test(secInputtest  ,val )
// {
//   if ( secInputtest!=3 && getTimerTime() > (val/2)  )
//   {
//     normaltest.innerHTML='Vous avez perdu , vous pouvez continuez a jouer ou recommencer';
//
//   }
// }

// let startTime
// function startTimer(test) {
//   timerElement.innerText = 0
//   startTime = new Date()
//   setInterval(() => {
//     timer.innerText = getTimerTime()
//   }, 1000)
// }
//
// function getTimerTime() {
//   return Math.floor((new Date() - startTime) / 1000)
// }

function countdown() {
  // Make sure time is not run out
  if (time > 0) {
    // Decrement
    time--;
  } else if (time === 0) {
    // Game is over
    isPlaying = false;
  }
  // Show time
  timerElement.innerHTML = time;
}

// Check game status
function checkStatus() {
  if (!isPlaying && time === 0) {
    normaltest.innerHTML = 'Game Over!!!';
    quoteDisplayElement.innerHTML='';
    i = -1;
    // var url3 = k1 + ' addrapidgame2/' + sessionStorage['highscore'] + '/' + currentLevel + '/rapide' + k2 + k3;
    // //url = url.replace(t1, y);
    // urlout.innerHTML = url3;
  }
  else if(!isPlaying && time === -2)
  {
    quoteDisplayElement.innerHTML='';
    timerElement.innerHTML ='';
  }
}

init()
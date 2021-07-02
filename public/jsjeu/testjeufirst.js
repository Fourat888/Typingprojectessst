window.addEventListener('load', init);

// Globals

// Available Levels
const levels = {
  easy: 5,
  medium: 3,
  hard: 1
};

// To change level
const secInput = document.querySelector('#secchose');
const secInputtest = parseInt(localStorage.getItem("Textvalue"));
//const currentLevel = levels.medium;

const currentLevel = secInputtest;

let time = currentLevel;
let score = 0;
let isPlaying;

// DOM Elements
const wordInput = document.querySelector('#word-input');
const urlout = document.querySelector('#urlout');

const currentWord = document.querySelector('#current-word');
const scoreDisplay = document.querySelector('#score');
const timeDisplay = document.querySelector('#time');
const message = document.querySelector('#message');
const seconds = document.querySelector('#seconds');
const highscoreDisplay = document.querySelector('#highscore');
const highscoreDisplay2 = document.querySelector('#highscore2');
var x1= 'addrapid_game2';
var x2= "'";

var k1= ' <a href="';
var k2= '">savescore</a';
var k3= '>';
var t1= 'score';
var v1= '9';
var t3= 'level';
var t4= 'partietype';

var tn= '<a href=' ;
var tn2= '>test <a';
var tn3='>';

//alert(tn+url+tn2+tn3);

const words = [
  'hat',
  'river',
  'lucky',
  'statue',
  'generate',
  'stubborn',
  'cocktail',
  'runaway',
  'joke',
  'developer',
  'establishment',
  'hero',
  'javascript',
  'nutrition',
  'revolver',
  'echo',
  'siblings',
  'investigate',
  'horrendous',
  'symptom',
  'laughter',
  'magic',
  'master',
  'space',
  'definition'
];
let y=0;
let hs=0;
// Initialize Game
function init() {
  sessionStorage['highscore']=0;

  var url = k1+'{{ path('+x2+x1+x2+', {'+x2+t1+x2+': '+v1+','+x2+t3+x2+': '+currentLevel+','+x2+t4+x2+': 89}) }}'+k2+k3;
  //url = url.replace(t1, y);
  if (sessionStorage['highscore'] >= 0) {
    highscoreDisplay.innerHTML = sessionStorage['highscore'];
  }
  // Show number of seconds in UI
  //seconds.innerHTML = currentLevel;
  seconds.innerHTML = currentLevel;  // Load word from array
  showWord(words);
  // Start matching on word input
  wordInput.addEventListener('input', startMatch);
  // Call countdown every second
  setInterval(countdown, 1000);
  // Check game status
  setInterval(checkStatus, 500);
}

// Start match
function startMatch() {
  seconds.innerHTML = currentLevel;  // Load word from array

  if (matchWords()) {
    isPlaying = true;
    //time = currentLevel + 1;
    time = currentLevel + 1;

    showWord(words);
    wordInput.value = '';
    score++;
  }

  // Highscore based on score value for Session Storage
  if (typeof sessionStorage['highscore'] === 'undefined' || score > sessionStorage['highscore']) {
    sessionStorage['highscore'] = score;
    sessionStorage['highscore2'] = score;
    y = score;
    localStorage.setItem("highscoree", score);
  } else {
    y = sessionStorage['highscore'];
    sessionStorage['highscore'] = sessionStorage['highscore'];
    sessionStorage['highscore2'] = sessionStorage['highscore'];

  }

  // Prevent display of High Score: -1
  if (sessionStorage['highscore'] >= 0) {
    highscoreDisplay.innerHTML = sessionStorage['highscore'];
  }
  /* if (sessionStorage['highscore2'] >= 0) {
    highscoreDisplay2.innerHTML = sessionStorage['highscore'];
  }*/
  // If score is -1, display 0
  if (score === -1) {
    scoreDisplay.innerHTML = 0;

  } else {
    scoreDisplay.innerHTML = score;
  }
}

// Match currentWord to wordInput
function matchWords() {
  if (wordInput.value === currentWord.innerHTML) {
    message.innerHTML = 'Correct!!!';
    return true;
  } else {
    message.innerHTML = '';
    return false;
  }
}

// Pick & show random word
function showWord(words) {
  // Generate random array index
  const randIndex = Math.floor(Math.random() * words.length);
  // Output random word
  currentWord.innerHTML = words[randIndex];
}

// Countdown timer
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
  timeDisplay.innerHTML = time;
}

// Check game status
function checkStatus() {
  if (!isPlaying && time === 0) {
    message.innerHTML = 'Game Over!!!';
    score = -1;
    var url3= k1+ ' addrapidgame2/'+sessionStorage['highscore']+'/'+currentLevel+'/rapide'+k2+k3;
    //url = url.replace(t1, y);
    urlout.innerHTML= url3 ;
  }

}
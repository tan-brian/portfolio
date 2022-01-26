import Game from './Game.js';
import Card from './Card.js';

export default class Player extends Game {
    constructor(el){
        super(),
        this._el = el,
        this._elBtnStop = this._el.querySelector('[data-js-stop]'),
        this._elBtnPlay = this._el.querySelector('[data-js-play]'),
        this._elHand = this._el.querySelector('[data-js-hand]'),
        this._elPoints =  this._el.querySelector('[data-js-points]'),
        this.elWinnerImg = this._el.querySelector('[data-js-winner]');
        this._points = []; 
        this.init();
    } 

    init = () => {
        this._elBtnPlay.addEventListener('click', this.play);
        this._elBtnStop.addEventListener('click', this.stop);
    }

    // Lorsque le joueur clique sur jouer
    play = (e) => {

        e.preventDefault();

        // Obtenir une carte
        let card = new Card(),
            number = card.value[0],
            suit = card.value[1];
            
        this._points.push(number);
        
        // Mise à joueur du pointage
        let points = this.calculatePoints();

        let img = document.createElement('img'),
            li = document.createElement('li');
        
        img.setAttribute('src', `./assets/img/${number}_of_${suit}.svg`);
        img.classList.add('slide-top');
        
        li.appendChild(img)
        
        this._elHand.appendChild(li)
        this._elPoints.innerHTML = points;
        
        // Si le joueur dépasse 21, il n'a plus le droit de jouer
        if(points > 21) {
            this._el.classList.add('game-over');
            this._elBtnStop.disabled = true;
            this._elBtnPlay.disabled = true;

        // Si le joueur obitent 21, il gagne et le jeu arrête
        } else if(points == 21){
            this.elWinnerImg.classList.remove('hide');
            this._el.classList.remove('current-player');
            this.showWinner()
        }

        if(points != 21)
        this.nextPlayer(this._el);
    }

    // lorsque le joueur clique sur stop, il ne peut plus jouer
    stop = (e) => {
        e.preventDefault();
        this._el.classList.add('stop');
        this._elBtnStop.disabled = true;
        this._elBtnPlay.disabled = true;
        this.nextPlayer(this._el)
    }

    // Calculer le pointage du joueur
    calculatePoints = () => {
        let points = 0;

        this._points.forEach(number => {
            if(isNaN(number)){
                points += 10;
            }else if(number == 1){
                points += 11;
            }else {
                points += parseInt(number);
            }
        });

        return points;
    }
    
 
}
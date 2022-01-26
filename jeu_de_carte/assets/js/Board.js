import Player from './Player.js';

export default class Board {
    constructor(playerNumber){
        this._playerNumber = playerNumber,
        this._elDivPlayers = document.querySelector('[data-js-players]');
        this.init()

    } 

    init = () => {

        this.createDOM();
        this.createPlayer();
    }

    // Créer les éléments DOM des joueurs
    createDOM = () => {
        let currentPlayer;

        for (let i = 1; i <= this._playerNumber; i++) {
            if(i == 1){
                currentPlayer = 'current-player';
            }else{ 
                currentPlayer = '';
            }
            this._elDivPlayers.insertAdjacentHTML('beforeend', `<form class="disabled ${currentPlayer}" data-js-player>
            <img data-js-winner class="winner-animation hide" src="" alt ="">
                                            <h2>Joueur ${i}</h2>
                                            <ul data-js-hand></ul>    
                                            <div>Total: <span data-js-points>0</span></div>
                                            <button data-js-play>Jouer</button>
                                            <button data-js-stop>Stop</button>
                                        </form>`);
            
        }
    }

    // Instantier les joueurs
    createPlayer = () => {
        let players = document.querySelectorAll('[data-js-player]');
         
        players.forEach(player => {
            new Player(player);
        })
    }
}
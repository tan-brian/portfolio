export default class Game{
    constructor(){
        this._players = document.querySelectorAll('[data-js-player]'),
        this._btnReplay = document.querySelector('[data-js-replay]'),
        this._elGame = document.querySelector('[data-js-game]');
        
        this.init();
    }
    
    init = () => {
        this._btnReplay.addEventListener('click', this.replay)
    }
    

    // Passer au joueur suivant
    nextPlayer = (currentPlayer) => {
        let nextPlayer = currentPlayer.nextSibling,
            stoppedPlayers = document.querySelectorAll('.stop'),
            loserPlayers = document.querySelectorAll('.game-over'),
            inactivePlayers = stoppedPlayers.length + loserPlayers.length;
        
        // Retourner au premier joueur après avoir passer tous les joueurs 
        if(nextPlayer == null){
            nextPlayer = currentPlayer.closest('[data-js-players]').firstChild;
        }
     
        currentPlayer.classList.remove('current-player');
        
        // Si le pochain joueur n'a pas déjà perdu ou n'a pas cliqué stop
        if(!nextPlayer.classList.contains('stop') && !nextPlayer.classList.contains('game-over') ){
            
            // Si tous les joueurs sauf le prochain joueur ont perdu, afficher le gagnant 
            if(loserPlayers.length != this._players.length - 1){

                // Sinon passer le tour au prochain joueur
                nextPlayer.classList.add('current-player');
            }else{
                this.showWinner();
            }
        }else {
            // Passer au prochain joueur qui est actif   
            if(inactivePlayers < this._players.length && loserPlayers.length < this._players.length){
                this.nextPlayer(nextPlayer);
            }else{
                // Si aucun joueur n'est actif, afficher gagnant  
                this.showWinner();
            } 
        }
    }

    // Afficher le gagnant
    showWinner = () => {
        
        let players = document.querySelectorAll('[data-js-player]:not(.game-over)'),
            points = document.querySelectorAll('[data-js-player]:not(.game-over) [data-js-points]'),
            highestPoints,
            indexes,
            nbGames = window.sessionStorage.getItem('nbGames');

            points = Array.prototype.map.call(points, (points) => { return parseInt(points.textContent); });

        highestPoints = Math.max(...points);

        // Trouver le nombre de gagants
        indexes = this.indexesOf(points, highestPoints);

        // Afficher le gagnant
        if(indexes.length == 1){
            players[indexes[0]].classList.add('winner');
            players[indexes[0]].querySelector('[data-js-winner]').src = "./assets/img/winner.gif";
            players[indexes[0]].querySelector('[data-js-winner]').classList.remove('hide');
        }else{
            // Afficher match nul si plusieurs ont le plus haut pointage
            indexes.forEach(index => {
                players[index].classList.add('winner');
            players[index].querySelector('[data-js-winner]').src = "./assets/img/draw.gif";
            players[index].querySelector('[data-js-winner]').classList.remove('hide');
            })
        }
        
        // Parties jouées
        if(!nbGames){
            window.sessionStorage.setItem('nbGames', 1);
        }else{
            window.sessionStorage.setItem('nbGames', parseInt(nbGames) + 1);
        }
       
        nbGames = window.sessionStorage.getItem('nbGames');
        
        this._btnReplay.classList.remove('hide');
        this._elGame.classList.remove('hide');
        this._elGame.innerHTML = `Parties jouées: ${nbGames}`;

        players.forEach(player => {
            let btnPlay = player.querySelector('[data-js-play]'),
                btnStop = player.querySelector('[data-js-stop]');

            btnPlay.disabled = true;
            btnStop.disabled = true;
        })
    }

    // Rejouer une partie
    replay = () => {
        let form = document.querySelector('[data-js-form]');

        this._btnReplay.classList.add('hide');
        this._elGame.classList.add('hide');
        form.classList.remove('hide')
        
        this._players.forEach(player => {
            player.remove();
        })
    }

    // https://stackoverflow.com/questions/36631641/javascript-indexof-method-with-multiple-values
    indexesOf = (arr, item) => arr.reduce((acc, v, i) => (v === item && acc.push(i), acc), []);
}
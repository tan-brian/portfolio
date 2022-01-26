import Board from './Board.js';

let elForm = document.querySelector('[data-js-form]'),
    elInputPlayer = elForm.querySelector('[data-js-input]'),
    elBtnPlay = elForm.querySelector('[data-js-submit]'),
    elMsgError = elForm.querySelector('[data-js-error]');

    elInputPlayer.addEventListener('input', () => {
        
        // Valider si le chiffre est plus grand que 0
        if(elInputPlayer.value > 0){
            elBtnPlay.disabled = false;
            elMsgError.innerHTML = '';
            elInputPlayer.classList.remove('error');
        }else{ 
            elBtnPlay.disabled = true;
            elMsgError.innerHTML = 'Le nombre doit être supérieur à 0.'
        }      
    })

    elBtnPlay.addEventListener('click', e => {
        e.preventDefault();
        elForm.classList.add('hide');
        new Board(elInputPlayer.value)
    })
    
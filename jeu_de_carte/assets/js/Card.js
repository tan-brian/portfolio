export default class Card{
    constructor(){
        this._numbers = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'jack', 'queen', 'king'],
        this._suits = ['clubs', 'spades', 'hearts', 'diamonds'];

        this._value = this.drawCard();
    }
    
    // Piger une carte
    drawCard = () => {
        let number = this._numbers[Math.floor(Math.random() * this._numbers.length)],
            suit = this._suits[Math.floor(Math.random() * this._suits.length)];

        return [number, suit];
    }

    get value(){
        return this._value;
    }

}
/***********
	Carousel
************/

const carouselContainer = document.querySelector('.slider-container');
const listImageArea = carouselContainer.querySelector('.next-list');
const listOfImages = listImageArea.querySelectorAll('img');
const currentImage = carouselContainer.querySelector('.current-image');
const arrowLeft = carouselContainer.querySelector('.arrow-left');
const arrowRight = carouselContainer.querySelector('.arrow-right');

function styleList() {
	if (listImageArea.scrollWidth == listImageArea.offsetWidth){
		listImageArea.style.justifyContent = 'center'
	} else {
		listImageArea.style.justifyContent = 'flex-start'
	}

};

function goToRight() {
	arrowLeft.classList.remove('disabled');
	
	var current = listImageArea.querySelector('.current-image-list');
	if(current.parentElement.nextElementSibling != null){

		if(current.parentElement.nextElementSibling.nextElementSibling == null){
			arrowRight.classList.add('disabled');
		}

		current.parentElement.nextElementSibling.children[0].classList.add('current-image-list');
		current.classList.remove('current-image-list');
		current = listImageArea.querySelector('.current-image-list');
		listImageArea.scrollLeft = current.offsetLeft;
		currentImage.attributes.src.value = current.attributes.src.value;
		currentImage.classList.add('slideInFromRight');
		setTimeout(function () {
			currentImage.classList.remove('slideInFromRight');
		}, 500);
	}
    zoom();
};

function goToLeft() {
	arrowRight.classList.remove('disabled');

	var current = listImageArea.querySelector('.current-image-list');

	if(current.parentElement.previousElementSibling != null){
		if(current.parentElement.previousElementSibling.previousElementSibling == null){
			arrowLeft.classList.add('disabled');
		}
		current.parentElement.previousElementSibling.children[0].classList.add('current-image-list');
		current.classList.remove('current-image-list');
		current = listImageArea.querySelector('.current-image-list');
		listImageArea.scrollLeft = current.offsetLeft;
		currentImage.attributes.src.value = current.attributes.src.value;
		currentImage.classList.add('slideInFromLeft');
		setTimeout(function () {
			currentImage.classList.remove('slideInFromLeft');
		}, 500);
	}
    zoom();
};

function changeCurrentImage () {
	currentImage.classList.add('fadeIn');
	setTimeout(function () {
		currentImage.classList.remove('fadeIn');
	}, 500);
	currentImage.attributes.src.value = this.attributes.src.value;

	listOfImages.forEach(function (image) {
		image.classList.remove('current-image-list');
	})
	this.classList.add('current-image-list');
}

styleList();

arrowLeft.addEventListener('click', goToLeft);
arrowRight.addEventListener('click', goToRight);

window.addEventListener('resize', function (e) {
	styleList();
});

(function () {
    if ( typeof NodeList.prototype.forEach === "function" ) return false;
    NodeList.prototype.forEach = Array.prototype.forEach;
})();

listOfImages.forEach(function(image) {
	image.addEventListener('click', changeCurrentImage);
	image.addEventListener('click', ()=> {
		if(image.closest('li').nextElementSibling == null){
			arrowRight.classList.add('disabled');
		}else {
			arrowRight.classList.remove('disabled');
		}

		if(image.closest('li').previousElementSibling == null){
			arrowLeft.classList.add('disabled');
		}else {
			arrowLeft.classList.remove('disabled');
		}
        zoom();
	})
});

zoom =() =>{
    document.querySelector('.img-zoomer-box')
      .addEventListener('mousemove', function(e) {
  
      var original = document.querySelector('.zoom-img1'),
          magnified = document.querySelector('.zoom-img2'),
          style = magnified.style,
          x = e.pageX,
          y = e.pageY,
          imgWidth = original.width,
          imgHeight = original.height,
          xperc = ((x/imgWidth) * 100),
          yperc = ((y/imgHeight) * 100);
        magnified.style.backgroundImage = "url('" + original.src + "')";
      if(x > (.01 * imgWidth)) {
        xperc += (1 * xperc);
      };//lets user scroll past right edge of image
  
      if(y >= (.01 * imgHeight)) {
        yperc += (1 * yperc);
      };//lets user scroll past bottom edge of image
	  if(window.innerWidth >= 1200){
		style.backgroundPositionX = (xperc - window.innerWidth *0.21) + '%';
		style.backgroundPositionY = (yperc - window.innerHeight *0.31) + '%';
	  }
    
  
	  style.left = (x - window.innerWidth *0.4) + 'px';
	  style.top = (y  - window.innerHeight *0.6 ) + 'px';
	  if(window.innerWidth < 1200){
		style.backgroundPositionX = (xperc - window.innerWidth *0.25) + '%';
		style.backgroundPositionY = (yperc - window.innerHeight *0.33) + '%';
	
	  }  if(window.innerWidth < 600){
		style.backgroundPositionX = (xperc - window.innerWidth *0.3) + '%';
		style.backgroundPositionY = (yperc - window.innerHeight *0.45) + '%';

	  }

	  if(window.innerWidth < 500){
		style.backgroundPositionX = (xperc - window.innerWidth *0.59) + '%';
		style.backgroundPositionY = (yperc - window.innerHeight *0.67) + '%';
		style.left = (x - window.innerWidth *0.58) + 'px';
		style.top = (y  - window.innerHeight *0.59 ) + 'px';
	  }

	  if(window.innerWidth < 470){
		style.backgroundPositionX = (xperc - window.innerWidth *0.53) + '%';
		style.backgroundPositionY = (yperc - window.innerHeight *0.72) + '%';

	  }

	  if(window.innerWidth < 400){
		style.backgroundPositionX = (xperc - window.innerWidth *0.65) + '%';
		style.backgroundPositionY = (yperc - window.innerHeight *0.8) + '%';
		
	  }

	  if(window.innerWidth < 370){
		style.backgroundPositionX = (xperc - window.innerWidth *0.0) + '%';
		style.backgroundPositionY = (yperc - window.innerHeight *0) + '%';
		
	  }
    }, false);
  };

  zoom();
  

  const modals = document.querySelectorAll('.modal-container');  
const active = document.querySelectorAll('.btn-info');
let link = document.querySelector('#payments');

const initModal = (modal) => {

    modal.classList.add('display')
    modal.addEventListener('click', (e) => {
        if(e.target.classList.contains('modal-container') || e.target.classList.contains('btn-close')){
            modal.classList.remove('display')
        }
    })
}


active[0].addEventListener('click', ()=> {
	modals[0].classList.add('display')
    modals[0].addEventListener('click', (e) => {
        if(e.target.classList.contains('modal-container') || e.target.classList.contains('btn-close')){
            modals[0].classList.remove('display')
        }
    })
});

active[1].addEventListener('click', ()=> {
	modals[1].classList.add('display')
    modals[1].addEventListener('click', (e) => {
        if(e.target.classList.contains('modal-container') || e.target.classList.contains('btn-close')){
            modals[1].classList.remove('display')
        }
    })
});

link.addEventListener('click', ()=> {
	console.log(modals)
	modals[2].classList.add('display')
    modals[2].addEventListener('click', (e) => {
        if(e.target.classList.contains('modal-container') || e.target.classList.contains('btn-close')){
            modals[2].classList.remove('display')
        }
    })
});

let counterThumbUp = 0,
	counterThumbDown = 0,
	thumbUp = document.querySelector('.thumb-up'),
	thumbDown = document.querySelector('.thumb-down'),
	thumbUpBtn = thumbUp.querySelector('i'),
	thumbDownBtn = thumbDown.querySelector('i'),
	thumbUpSpan = thumbUp.querySelector('span'),
	thumbDownSpan = thumbDown.querySelector('span'),
	inputReaction = document.querySelector('[data-js-reaction]');

	thumbDownBtn.addEventListener('click', ()=> {
		counterThumbDown++
		thumbDownSpan.innerText = parseInt(thumbDownSpan.innerText) + counterThumbDown;
		thumbDownBtn.classList.add('disabled');
		thumbUpBtn.classList.add('disabled');
		commentErreur.textContent = '';
		inputReaction.value = 'negatif';
	})

	thumbUpBtn.addEventListener('click', ()=> {
		counterThumbUp++;
		thumbUpSpan.innerText = parseInt(thumbUpSpan.innerText) + counterThumbUp;
		thumbDownBtn.classList.add('disabled');
		thumbUpBtn.classList.add('disabled');
		commentErreur.textContent = '';
		inputReaction.value = 'positive';
	})


let linkExpedition = document.querySelector('[data-expedition]'),
	linkSeller = document.querySelector('[data-seller]'),
	expedition = document.querySelector('#expedition'),
	seller = document.querySelector('#seller');

	linkExpedition.addEventListener('click',()=>{
		expedition.open = true;
	})

	linkSeller.addEventListener('click',()=>{
		seller.open = true;
	})

	let btnComment = document.querySelector('[data-js-btnComment]'),
		commentErreur = document.querySelector('[data-js-comment-erreur]'),
		formComment = document.querySelector('[data-js-form-comment]'),
		textComment ;
		if(formComment !=null ) {
			textComment = formComment.querySelector('textarea');
	btnComment.addEventListener('click', (e) => {

		e.preventDefault();
		if( counterThumbUp ==0 && counterThumbDown == 0) {
			commentErreur.textContent = 'Veuillez choisir une des options ci-dessus.';
		}else if(textComment.value.trim() == '') {
			commentErreur.textContent = 'Veuillez écrire un commentaire';
		}
		else {
			formComment.submit();
		}
	})
}

let commentSection = document.querySelector('[data-js-section-commentaire]'),
	btnModifiers = commentSection.querySelectorAll('[data-js-modifier]'),
	formModifier = commentSection.querySelectorAll('[data-js-form-modifier]'),
	btnSubmitModifiers = document.querySelectorAll('[data-js-btnCommentModifier]'),
	modifierErreur = document.querySelectorAll('[data-js-modifier-erreur]'),
	modifierTexte = document.querySelectorAll('[data-js-texte-modifier]');
	for (let i = 0; i < btnModifiers.length; i++) {
		btnModifiers[i].addEventListener('click', (e)=> {
			e.preventDefault();
			formModifier[i].open = true;
			formModifier[i].classList.remove('visually-hidden');
		})
	}

	for (let i = 0; i < btnSubmitModifiers.length; i++) {
		btnSubmitModifiers[i].addEventListener('click', (e) => {
			e.preventDefault();
			if(modifierTexte[i].value.trim() == '') {
				modifierErreur[i].textContent = 'Veuillez écrire un commentaire';
			}else {
				formModifier[i].querySelector('form').submit();
			}
		})
	}

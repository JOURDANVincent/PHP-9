let plan = document.querySelectorAll('.date');
let titleModal = document.querySelector('.modal-title');
let monthYear = document.querySelector('.calendarTitle');
let hiddenEvent = document.querySelector('.hiddenEvent');
let dateOfDay = document.querySelector('.dateOfDay');


let eventSelected = document.querySelector('.eventSelected');
let day = null;
//console.log(monthYear);


plan.forEach((item, index) => {
    item.addEventListener('click', getActualDate);
})

function getActualDate() {

    console.log('getDate', this);
    day = this.innerHTML;
    titleModal.innerHTML = this.innerHTML + " " + monthYear.innerHTML;
    dateOfDay.innerHTML = this.innerHTML + " " + monthYear.innerHTML;
    //console.log("dateOfDay",dateOfDay);

    // recupère la valeur du jour sélectionné
    hiddenEvent.setAttribute('value', day);   
    console.log(hiddenEvent);

    // affichage des événements à une date sélectionnée
    if (this.getAttribute('data-name') != null) {
        eventSelected.innerHTML = this.getAttribute('data-type') + ' de ' + this.getAttribute('data-name');
    } else {
        eventSelected.innerHTML = 'aucun événement pour le moment..';
    }

};


let addEvent = document.querySelector('.addEvent');
addEvent.addEventListener('click', addEventColor)

function addEventColor() {

    this.classList.toggle('eventInside');
    console.log(this);

}
//
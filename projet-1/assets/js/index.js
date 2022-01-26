"use strict";

// function that creates dummy data for demonstration
function createDummyData() {

  var data = {};
 
    data[2021] = {};
  
      data[2021][6] = {};

    
    
          data[2021][6][3] = [];
          data[2021][6][3].push({
            startTime: "10:00",
            endTime: "12:00 EDT",
            text: "Rhodesia 1910-13 Double Head Issue: The Cottonwood Collection"
          });
        
          data[2021][6][11] = [];
          data[2021][6][11].push({
            startTime: "11:00",
            endTime: "16:00 EDT",
            text: "U.S., Canada and Worldwide including Collections and Accumulations"
          });

          data[2021][6][11].push({
            startTime: "13:00",
            endTime: "15:00 EDT",
            text: "Spring Sale featuring U.S. Postage, U.S. B.O.B., Revenues & Worldwide Stamps"
          });
    
          data[2021][7] = {};

    
    
          data[2021][7][3] = [];
          data[2021][7][3].push({
            startTime: "9:00",
            endTime: "12:00 EDT",
            text: "Great Britain & British Empire Public Auction"
          });
    

  return data;
}

// creating the dummy static data
var data = createDummyData();

// initializing a new calendar object, that will use an html container to create itself
var calendar = new Calendar(
  "calendarContainer", // id of html container for calendar
  "small", // size of calendar, can be small | medium | large
  [
    "Wednesday", // left most day of calendar labels
    3 // maximum length of the calendar labels
  ],
  [
    "#2d5dce", // primary color
    "#0136b1", // primary dark color
    "#FFFFFF", // text color
    "#FFFFFF" // text dark color
  ]
);

// initializing a new organizer object, that will use an html container to create itself
var organizer = new Organizer(
  "organizerContainer", // id of html container for calendar
  calendar, // defining the calendar that the organizer is related to
  data // giving the organizer the static data that should be displayed
);


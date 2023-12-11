<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">

<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="http://127.0.0.1:8000/">
            <img src="https://bulma.io/images/bulma-logo.png" width="112" height="28">
        </a>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">
            <a class="navbar-item">
                Home
            </a>
        </div>
    </div>
    </div>
</nav>

<style>
    body {
        font-family: 'Arial', sans-serif;
    }

    #cardForm {
        max-width: 600px;
        margin: 20px auto;
    }

    .cardContainer {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .card {
        position: relative;
        width: 360px;
        height: 600px;
        border: 1px solid #ccc;
        overflow: hidden;
    }

    .card img {
        width: 100%;
        height: auto;
    }

    .cardContent {
        padding: 10px;
    }

    .deleteButton {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background-color: #ff6262;
        color: #fff;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
    }

    .emoji {
        font-size: 20px;
        margin-right: 5px;
    }
</style>
</head>

<body>

    <div id="cardForm">
        <label for="imageInput">Choose an image:</label>
        <input type="file" id="imageInput" accept="image/*">
        <br>

        <label for="nameInput">Name:</label>
        <input type="text" id="nameInput">
        <br>

        <label for="priceInput">Price:</label>
        <input type="text" id="priceInput">
        <br>

        <label for="spicinessInput">Spiciness (1-5):</label>
        <input type="number" id="spicinessInput" min="1" max="5">
        <br>

        <label for="glutenFreeInput">Is Gluten-Free:</label>
        <input type="checkbox" id="glutenFreeInput">
        <br>

        <label for="vegetarianInput">Is Vegetarian:</label>
        <input type="checkbox" id="vegetarianInput">
        <br>

        <label for="veganInput">Is Vegan:</label>
        <input type="checkbox" id="veganInput">
        <br>

        <label for="descriptionInput">Description:</label>
        <textarea id="descriptionInput"></textarea>
        <br>

        <button onclick="createCard()">Create Card</button>
    </div>

    <div class="cardContainer" id="cardContainer"></div>

    <script>
        window.onload = function() {
            loadCards();
        };

        function createCard() {

            var imageUrl = document.getElementById('imageInput').files[0];
            var name = document.getElementById('nameInput').value;
            var price = document.getElementById('priceInput').value;
            var spiciness = document.getElementById('spicinessInput').value;
            var glutenFree = document.getElementById('glutenFreeInput').checked;
            var vegetarian = document.getElementById('vegetarianInput').checked;
            var vegan = document.getElementById('veganInput').checked;
            var description = document.getElementById('descriptionInput').value;


            price = price + "€";


            var reader = new FileReader();
            reader.onload = function(e) {
                var imageData = e.target.result;


                var newCard = document.createElement('div');
                newCard.className = 'card';


                var spicinessEmojis = '';
                if (spiciness && spiciness >= 1 && spiciness <= 5) {
                    spicinessEmojis = '🌶️'.repeat(spiciness);
                }

                var glutenFreeEmoji = glutenFree ? '✅' : '❌';
                var vegetarianEmoji = vegetarian ? '✅' : '❌';
                var veganEmoji = vegan ? '✅' : '❌';

                var cardContent = `
        <div class="card-image">
          <figure class="image is-4by3">
            <img src="${imageData}" alt="Preview Image">
          </figure>
        </div>
        <div class="card-content cardContent">
          <div class="media">
            <div class="media-content">
              <p class="title is-4">${name}</p>
              <p class="subtitle is-6">${price}</p>
              <p class="emoji">${glutenFreeEmoji} Gluten-Free</p>
              <p class="emoji">${vegetarianEmoji} Vegetarian</p>
              <p class="emoji">${veganEmoji} Vegan</p>
              <p class="emoji">${spicinessEmojis} Spiciness</p>
              <p>${description}</p>
            </div>
          </div>
        </div>
        <button class="deleteButton" onclick="deleteCard(this.parentNode)">Delete</button>
      `;

                newCard.innerHTML = cardContent;


                document.getElementById('cardContainer').appendChild(newCard);


                saveCardData(imageData, name, price, glutenFree, vegetarian, vegan, spiciness, description);

                document.getElementById('imageInput').value = '';
                document.getElementById('nameInput').value = '';
                document.getElementById('priceInput').value = '';
                document.getElementById('spicinessInput').value = '';
                document.getElementById('glutenFreeInput').checked = false;
                document.getElementById('vegetarianInput').checked = false;
                document.getElementById('veganInput').checked = false;
                document.getElementById('descriptionInput').value = '';
            };

            reader.readAsDataURL(imageUrl);
        }

        function saveCardData(imageData, name, price, glutenFree, vegetarian, vegan, spiciness, description) {

            var existingData = localStorage.getItem('cardData') || '[]';
            var cardData = JSON.parse(existingData);

            cardData.push({
                imageData: imageData,
                name: name,
                price: price,
                glutenFree: glutenFree,
                vegetarian: vegetarian,
                vegan: vegan,
                spiciness: spiciness,
                description: description
            });

            localStorage.setItem('cardData', JSON.stringify(cardData));
        }

        function loadCards() {

            var existingData = localStorage.getItem('cardData') || '[]';
            var cardData = JSON.parse(existingData);

            for (var i = 0; i < cardData.length; i++) {
                var card = document.createElement('div');
                card.className = 'card';

                var spicinessEmojis = '';
                if (cardData[i].spiciness && cardData[i].spiciness >= 1 && cardData[i].spiciness <= 5) {
                    spicinessEmojis = '🌶️'.repeat(cardData[i].spiciness);
                }

                var glutenFreeEmoji = cardData[i].glutenFree ? '✅' : '❌';
                var vegetarianEmoji = cardData[i].vegetarian ? '✅' : '❌';
                var veganEmoji = cardData[i].vegan ? '✅' : '❌';

                var cardContent = `
        <div class="card-image">
          <figure class="image is-4by3">
            <img src="${cardData[i].imageData}" alt="Preview Image">
          </figure>
        </div>
        <div class="card-content cardContent">
          <div class="media">
            <div class="media-content">
              <p class="title is-4">${cardData[i].name}</p>
              <p class="subtitle is-6">${cardData[i].price}</p>
              <p class="emoji">${glutenFreeEmoji} Gluten-Free</p>
              <p class="emoji">${vegetarianEmoji} Vegetarian</p>
              <p class="emoji">${veganEmoji} Vegan</p>
              <p class="emoji">${spicinessEmojis} Spiciness</p>
              <p>${cardData[i].description}</p>
            </div>
          </div>
          <button class="deleteButton" onclick="deleteCard(this.parentNode)">Delete</button>
        </div>
      `;

                card.innerHTML = cardContent;

                document.getElementById('cardContainer').appendChild(card);
            }
        }

        function deleteCard(cardElement) {

            cardElement.parentNode.removeChild(cardElement);

            var existingData = localStorage.getItem('cardData') || '[]';
            var cardData = JSON.parse(existingData);

            var index = Array.from(cardElement.parentNode.children).indexOf(cardElement);

            cardData.splice(index, 1);

            localStorage.setItem('cardData', JSON.stringify(cardData));

            loadCards();
        }
    </script>
const crops = [
    { name: 'Almond', image: '/images/crops/Almond.jpg' },
    { name: 'Apple', image: '/images/crops/Apple.jpg' },
    { name: 'Apricot', image: '/images/crops/Apricot.jpg' },
    { name: 'Arum', image: '/images/crops/Arum.jpg' },
    { name: 'Bajra', image: '/images/crops/Bajra.jpg' },
    { name: 'Banana', image: '/images/crops/Banana.jpg' },
    { name: 'Barley', image: '/images/crops/Barley.jpg' },
    { name: 'Beetroot', image: '/images/crops/Beetroot.jpg' },
    { name: 'Ber', image: '/images/crops/Ber.jpg' },
    { name: 'Bitter gourd', image: '/images/crops/Bittergourd.jpg' },
    { name: 'Bottle gourd', image: '/images/crops/Bottlegourd.jpg' },
    { name: 'Brinjal', image: '/images/crops/Brinjal.jpg' },
    { name: 'Cabbage', image: '/images/crops/Cabbage.jpg' },
    { name: 'Carrot', image: '/images/crops/Carrot.jpg' },
    { name: 'Cauliflower', image: '/images/crops/Cauliflower.jpg' },
    { name: 'Chikoo', image: '/images/crops/Chikoo.jpg' },
    { name: 'Coconut', image: '/images/crops/Coconut.jpg' },
    { name: 'Coffee', image: '/images/crops/Coffee.jpg' },
    { name: 'Corn', image: '/images/crops/Corn.jpg' },
    { name: 'Corriander', image: '/images/crops/Corriander.jpg' },
    { name: 'Cotton', image: '/images/crops/Cotton.jpg' },
    { name: 'Cucumber', image: '/images/crops/Cucumber.jpg' },
    { name: 'Dates', image: '/images/crops/Dates.jpg' },
    { name: 'Falsa', image: '/images/crops/Falsa.jpg' },
    { name: 'Figs', image: '/images/crops/Figs.jpg' },
    { name: 'Garlic', image: '/images/crops/Garlic.jpg' },
    { name: 'Goldwheat', image: '/images/crops/Goldwheat.jpg' },
    { name: 'Grapefruit', image: '/images/crops/Grapefruit.jpg' },
    { name: 'Green grapes', image: '/images/crops/Greengrapes.jpg' },
    { name: 'Greenwheat', image: '/images/crops/Greenwheat.jpg' },
    { name: 'Guava', image: '/images/crops/Guava.jpg' },
    { name: 'Jowar', image: '/images/crops/Jowar.jpg' },
    { name: 'Lemon', image: '/images/crops/Lemon.jpg' },
    { name: 'Lettuce', image: '/images/crops/Lettuce.jpg' },
    { name: 'Lichi', image: '/images/crops/Lichi.jpg' },
    { name: 'Linseed', image: '/images/crops/Linseed.jpg' },
    { name: 'Long melon', image: '/images/crops/Longmelon.jpg' },
    { name: 'Loquat', image: '/images/crops/Loquat.jpg' },
    { name: 'Luffa', image: '/images/crops/Luffa.jpg' },
    { name: 'Maize', image: '/images/crops/Maize.jpg' },
    { name: 'Mandarine', image: '/images/crops/Mandarine.jpg' },
    { name: 'Mango', image: '/images/crops/Mango.jpg' },
    { name: 'Masoor', image: '/images/crops/Masoor.jpg' },
    { name: 'Mulberry', image: '/images/crops/Mulberry.jpg' },
    { name: 'Mung', image: '/images/crops/Mung.jpg' },
    { name: 'Musk melon', image: '/images/crops/Muskmelon.jpg' },
    { name: 'Mustard', image: '/images/crops/Mustard.jpg' },
    { name: 'Musubi', image: '/images/crops/Musubi.jpg' },
    { name: 'Onion', image: '/images/crops/Onion.jpg' },
    { name: 'Orange', image: '/images/crops/Orange.jpg' },
    { name: 'Papaya', image: '/images/crops/Papaya.jpg' },
    { name: 'Peaches', image: '/images/crops/Peaches.jpg' },
    { name: 'Peanuts', image: '/images/crops/Peanuts.jpg' },
    { name: 'Pear', image: '/images/crops/Pear.jpg' },
    { name: 'Peas', image: '/images/crops/Peas.jpg' },
    { name: 'Pineapple', image: '/images/crops/Pineapple.jpg' },
    { name: 'Plums', image: '/images/crops/Plums.jpg' },
    { name: 'Pomegrante', image: '/images/crops/Pomegrante.jpg' },
    { name: 'Potato', image: '/images/crops/Potato.jpg' },
    { name: 'Pumpkin', image: '/images/crops/Pumpkin.jpg' },
    { name: 'Radish', image: '/images/crops/Radish.jpg' },
    { name: 'Red chilli', image: '/images/crops/Redchilli.jpg' },
    { name: 'Red grapes', image: '/images/crops/Redgrapes.jpg' },
    { name: 'Redbellpeppers', image: '/images/crops/Redbellpeppers.jpg' },
    { name: 'Rice', image: '/images/crops/Rice.jpg' },
    { name: 'Safflower', image: '/images/crops/Safflower.jpg' },
    { name: 'Seasamum', image: '/images/crops/Seasamum.jpg' },
    { name: 'Sour lime', image: '/images/crops/Sourlime.jpg' },
    { name: 'Sour orange', image: '/images/crops/Sourorange.jpg' },
    { name: 'Soyabean', image: '/images/crops/Soyabean.jpg' },
    { name: 'Soybean', image: '/images/crops/Soybean.jpg' },
    { name: 'Spinach', image: '/images/crops/Spinach.jpg' },
    { name: 'Strawberry', image: '/images/crops/Strawberry.jpg' },
    { name: 'Sugarcane', image: '/images/crops/Sugarcane.jpg' },
    { name: 'Sunflower', image: '/images/crops/Sunflower.jpg' },
    { name: 'Sweet lime', image: '/images/crops/Sweetlime.jpg' },
    { name: 'Sweet potato', image: '/images/crops/Sweetpotato.jpg' },
    { name: 'Tobacco', image: '/images/crops/Tobacco.jpg' },
    { name: 'Tomato', image: '/images/crops/Tomato.jpg' },
    { name: 'Turmeric', image: '/images/crops/Turmeric.jpg' },
    { name: 'Turnip', image: '/images/crops/Turnip.jpg' },
    { name: 'Walnut', image: '/images/crops/Walnut.jpg' },
    { name: 'Watermelon', image: '/images/crops/Watermelon.jpg' },
    { name: 'Wheat', image: '/images/crops/Wheat.jpg' },
    { name: 'Alfafa', image: '/images/crops/Alfafa.jpg' },
    { name: 'Sorghum', image: '/images/crops/Sorghum.jpg' },
    { name: 'Fodder', image: '/images/crops/Fodder.jpg' },
    { name: 'Silage', image: '/images/crops/Silage.jpg' },
    { name: 'Vegetables', image: '/images/crops/Vegetables.jpg' },
    { name: 'Canola', image: '/images/crops/Canola.jpg' },
    { name: 'Berseem', image: '/images/crops/Berseem.jpg' },
    { name: 'Jaggery', image: '/images/crops/Jaggery.jpg' },
    { name: 'Straw', image: '/images/crops/Straw.jpg' },

];


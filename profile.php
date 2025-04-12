<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}

$user_id = $_SESSION['user_id'];

// Save preferences if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $activity = $_POST['activity'];
  $budget = $_POST['budget'];
  $location = $_POST['location'];
  $duration = $_POST['duration'];
  $climate = $_POST['climate'];
  $visa_free = $_POST['visa_free'];

  $update = $conn->prepare("UPDATE users SET activity=?, budget=?, location=?, duration=?, climate=?, visa_free=? WHERE id=?");
  $update->bind_param("ssssssi", $activity, $budget, $location, $duration, $climate, $visa_free, $user_id);
  $update->execute();
  $update->close();
}

// Fetch user info
$stmt = $conn->prepare("SELECT name, email, activity, budget, location, duration, climate, visa_free FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $activity, $budget, $location, $duration, $climate, $visa_free);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Your Profile | TravelMate</title>
  <link rel="stylesheet" href="profile.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>

  <header>
    <div class="logo">TravelMate</div>
    <nav>
      <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="profile.php" class="active">Profile</a></li>
        <li><a href="about.html">About Us</a></li>
        <li><a href="contact.html">Contact</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <div class="profile-container">
    <div class="profile-header">
      <img src="https://i.pinimg.com/736x/3c/f7/bc/3cf7bca392a994ca5b5d47104074a32d.jpg" alt="User Avatar" class="profile-avatar">
      <div>
        <h2>Welcome, <?php echo htmlspecialchars($name); ?> 👋🏻</h2>
        <p>Ready to explore your next destination?</p>
      </div>
    </div>


    <?php if (!$activity || !$budget || !$location): ?>
      <form method="POST" class="pref-form">
        <h3>Select Your Preferences</h3>
        <select name="activity" required>
          <option value="">Select Activity</option>
          <option value="adventure">Adventure</option>
          <option value="relaxation">Relaxation</option>
          <option value="sightseeing">Sightseeing</option>
          <option value="hiking">Hiking</option>
          <option value="trekking">Trekking</option>
          <option value="skydiving">Skydiving</option>
          <option value="scubadiving">Scubadiving</option>
          <option value="museums">Museums</option>
          <option value="historicalplaces">Historical places</option>
        </select>

        <select name="budget" required>
          <option value="">Select Budget</option>
          <option value="budget">Budget-Friendly</option>
          <option value="midrange">Mid Range</option>
          <option value="luxury">Luxury</option>
        </select>

        <select name="location" required>
          <option value="">Select Location</option>
          <option value="mountains">Mountains</option>
          <option value="beach">Beach</option>
          <option value="historical">Historical Place</option>
          <option value="city">City</option>
        </select>

        <select name="duration" required>
          <option value="">Trip Duration</option>
          <option value="1-3 days">1-3 days</option>
          <option value="4-7 days">4-7 days</option>
          <option value="1-2 weeks">1-2 weeks</option>
          <option value="More than 2 weeks">More than 2 weeks</option>
        </select>

        <select name="climate" required>
          <option value="">Preferred Climate</option>
          <option value="tropical">Tropical</option>
          <option value="cold">Cold</option>
          <option value="moderate">Moderate</option>
        </select>

        <select name="visa_free" required>
          <option value="">Visa-Free?</option>
          <option value="yes">Yes</option>
          <option value="no">No</option>
        </select>

        <button type="submit">Save Preferences</button>
      </form>
    <?php else: ?>
      <div class="preferences two-column enhanced-preferences">
        <h3>Your Preferences</h3>
        <div class="preference-columns">
          <ul class="left-pref">
            <li>🧭 <strong>Activity:</strong> <?php echo ucfirst($activity); ?></li>
            <li>💰 <strong>Budget:</strong> <?php echo ucfirst($budget); ?></li>
            <li>📍 <strong>Location:</strong> <?php echo ucfirst($location); ?></li>
          </ul>
          <ul class="right-pref">
            <li>🕒 <strong>Duration:</strong> <?php echo $duration; ?></li>
            <li>🌤️ <strong>Climate:</strong> <?php echo ucfirst($climate); ?></li>
            <li>🛂 <strong>Visa-Free:</strong> <?php echo ucfirst($visa_free); ?></li>
          </ul>
        </div>
      </div>

      <div class="travel-quote">
        ✈️ “The world is a book and those who do not travel read only one page.” — St. Augustine
      </div>




      <div class="recommendations">
        <h3>Recommended Destinations</h3>
        <ul>
          <?php
          $recs = [];

          if ($activity === "adventure" && $location === "mountains" && $climate === "moderate" && $visa_free === "yes") {
            $recs = ["Rishikesh, India", "Kasol, India", "Spiti Valley, India", "Ziro Valley, India"];
          } elseif ($activity === "adventure" && $location === "mountains" && $climate === "cold" && $visa_free === "no") {
            $recs = ["Leh-Ladakh, India", "Gulmarg, India", "Auli, India", "Pahalgam, India"];
          } elseif ($activity === "adventure" && $location === "beach" && $climate === "hot" && $visa_free === "yes") {
            $recs = ["Goa, India", "Rameswaram, India", "Pondicherry, India"];
          } elseif ($activity === "adventure" && $location === "beach" && $climate === "hot" && $visa_free === "no") {
            $recs = ["Bali, Indonesia", "Phuket, Thailand", "Boracay, Philippines"];
          } elseif ($activity === "relaxation" && $location === "beach" && $climate === "hot" && $visa_free === "yes") {
            $recs = ["Goa, India", "Andaman Islands, India", "Varkala, India", "Kovalam, India"];
          } elseif ($activity === "relaxation" && $location === "beach" && $climate === "hot" && $visa_free === "no") {
            $recs = ["Maldives", "Bora Bora", "Seychelles", "Mauritius"];
          } elseif ($activity === "relaxation" && $location === "mountains" && $climate === "moderate") {
            $recs = ["Ooty, India", "Nainital, India", "Munnar, India", "Coorg, India"];
          } elseif ($activity === "sightseeing" && $location === "city" && $visa_free === "yes") {
            $recs = ["Delhi, India", "Mumbai, India", "Jaipur, India", "Hyderabad, India"];
          } elseif ($activity === "sightseeing" && $location === "city" && $visa_free === "no") {
            $recs = ["Paris, France", "Rome, Italy", "Istanbul, Turkey", "Bangkok, Thailand"];
          } elseif ($activity === "sightseeing" && $location === "historical" && $visa_free === "yes") {
            $recs = ["Hampi, India", "Udaipur, India", "Thanjavur, India"];
          } elseif ($activity === "sightseeing" && $location === "historical" && $visa_free === "no") {
            $recs = ["Kyoto, Japan", "Petra, Jordan", "Florence, Italy"];
          } elseif ($activity === "hiking" && $location === "mountains" && $visa_free === "yes") {
            $recs = ["Triund, Himachal", "Chopta, Uttarakhand", "Tirthan Valley, India", "Chembra Peak, Kerala"];
          } elseif ($activity === "hiking" && $location === "mountains" && $visa_free === "no") {
            $recs = ["Ella, Sri Lanka", "Langtang, Nepal", "Pokhara, Nepal"];
          } elseif ($activity === "trekking" && $location === "mountains" && $visa_free === "yes") {
            $recs = ["Valley of Flowers, India", "Har Ki Dun, India", "Kedarkantha, India"];
          } elseif ($activity === "trekking" && $location === "mountains" && $visa_free === "no") {
            $recs = ["Annapurna Base Camp, Nepal", "Everest Base Camp, Nepal", "Tiger's Nest, Bhutan"];
          } elseif ($activity === "skydiving" && $visa_free === "yes") {
            $recs = ["Mysore, India", "Pondicherry, India", "Aamby Valley, India"];
          } elseif ($activity === "skydiving" && $visa_free === "no") {
            $recs = ["Interlaken, Switzerland", "Dubai, UAE", "Hawaii, USA"];
          } elseif ($activity === "scubadiving" && $climate === "hot" && $visa_free === "yes") {
            $recs = ["Havelock Island, India", "Netrani Island, India", "Lakshadweep, India"];
          } elseif ($activity === "scubadiving" && $climate === "hot" && $visa_free === "no") {
            $recs = ["Great Barrier Reef, Australia", "Red Sea, Egypt", "Komodo Island, Indonesia"];
          } elseif ($activity === "museums" && $location === "city" && $visa_free === "yes") {
            $recs = ["Delhi, India", "Kolkata, India", "Bangalore, India", "Ahmedabad, India"];
          } elseif ($activity === "museums" && $location === "city" && $visa_free === "no") {
            $recs = ["London, UK", "Berlin, Germany", "New York City, USA"];
          } elseif ($activity === "historicalplaces" && $location === "historical" && $visa_free === "yes") {
            $recs = ["Agra, India", "Hampi, India", "Madurai, India", "Khajuraho, India", "Jaipur, India", "Gwalior, India", "Orchha, India", "Mysore, India"];
          } elseif ($activity === "historicalplaces" && $location === "historical" && $visa_free === "no") {
            $recs = ["Cairo, Egypt", "Athens, Greece", "Rome, Italy", "Angkor Wat, Cambodia"];
          }

          if (empty($recs)) {
            $recs = ["Oslo, Norway", "Zurich, Switzerland", "Reykjavík, Iceland"];
          }

          $destinationImages = [
            "Rishikesh, India" => "https://images.pexels.com/photos/5205768/pexels-photo-5205768.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
            "Leh-Ladakh, India" => "https://images.pexels.com/photos/951927/pexels-photo-951927.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Kasol, India" => "https://images.pexels.com/photos/2961109/pexels-photo-2961109.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Spiti Valley, India" => "https://images.pexels.com/photos/31539177/pexels-photo-31539177/free-photo-of-scenic-sunrise-over-faroe-islands-fjord.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Ziro Valley, India" => "https://images.pexels.com/photos/31443434/pexels-photo-31443434/free-photo-of-scenic-view-of-terraced-farmlands-in-himachal-pradesh.jpeg?auto=compress&cs=tinysrgb&w=600",

            "Maldives" => "https://images.pexels.com/photos/1483053/pexels-photo-1483053.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Bora Bora" => "https://images.pexels.com/photos/1628086/pexels-photo-1628086.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Seychelles" => "https://images.pexels.com/photos/2956470/pexels-photo-2956470.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Goa, India" => "https://images.pexels.com/photos/457882/pexels-photo-457882.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Andaman Islands, India" => "https://images.pexels.com/photos/10671403/pexels-photo-10671403.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Varkala, India" => "https://images.pexels.com/photos/27566375/pexels-photo-27566375/free-photo-of-varkala-cliff-beach.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Kovalam, India" => "https://images.pexels.com/photos/30672700/pexels-photo-30672700/free-photo-of-colorful-fishing-boats-in-kovalam-harbor.jpeg?auto=compress&cs=tinysrgb&w=600",

            "Paris, France" => "https://i.pinimg.com/736x/6a/99/ee/6a99ee843798375c5f7049316e8d31ed.jpg",
            "Rome, Italy" => "https://images.pexels.com/photos/2064827/pexels-photo-2064827.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Istanbul, Turkey" => "https://images.pexels.com/photos/1549326/pexels-photo-1549326.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Bangkok, Thailand" => "https://images.pexels.com/photos/50689/skytrain-thailand-transportation-sky-50689.jpeg?auto=compress&cs=tinysrgb&w=600",

            "Delhi, India" => "https://images.pexels.com/photos/789750/pexels-photo-789750.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Mumbai, India" => "https://i.pinimg.com/736x/cb/18/0f/cb180ffde7c3abaaf56df8aa5153300e.jpg",
            "Jaipur, India" => "https://images.pexels.com/photos/784879/pexels-photo-784879.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
            "Hyderabad, India" => "https://i.pinimg.com/736x/26/0d/b2/260db2e7ce35b37624b5f0889b116cd1.jpg",

            "Triund, Himachal" => "https://images.pexels.com/photos/27462660/pexels-photo-27462660/free-photo-of-triund-peak.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Chopta, Uttarakhand" => "https://images.pexels.com/photos/19877297/pexels-photo-19877297/free-photo-of-chopta-tungnath-chandrashila-trek.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Tirthan Valley, India" => "https://images.pexels.com/photos/31443434/pexels-photo-31443434/free-photo-of-scenic-view-of-terraced-farmlands-in-himachal-pradesh.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Chembra Peak, Kerala" => "https://i.pinimg.com/474x/89/66/97/896697b8a5b057cf27a78b8c809b37b9.jpg",
            "Ella, Sri Lanka" => "https://images.pexels.com/photos/31572664/pexels-photo-31572664/free-photo-of-scenic-waterfall-in-lush-sri-lankan-jungle.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",

            "Valley of Flowers, India" => "https://i.pinimg.com/736x/1d/7e/9a/1d7e9a2899ba4658bf11910458537700.jpg",
            "Har Ki Dun, India" => "https://i.pinimg.com/736x/34/9f/ad/349faddd4c926d05c36dd31fbd5e939e.jpg",
            "Kedarkantha, India" => "https://images.pexels.com/photos/7846665/pexels-photo-7846665.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
            "Langtang, Nepal" => "https://images.pexels.com/photos/16087994/pexels-photo-16087994/free-photo-of-colorful-fabrics-over-rocks-in-valley.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Annapurna Base Camp, Nepal" => "https://i.pinimg.com/736x/0f/89/18/0f89182080238d0f222b5e16fa79e789.jpg",
            "Everest Base Camp, Nepal" => "https://i.pinimg.com/736x/68/db/1f/68db1f2c8650e37436d52f06feb42767.jpg",
            "Tiger's Nest, Bhutan" => "https://i.pinimg.com/736x/98/f8/35/98f835f86d4379dd6796ba5683123089.jpg",

            "Interlaken, Switzerland" => "https://images.pexels.com/photos/922978/pexels-photo-922978.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Dubai, UAE" => "https://images.pexels.com/photos/2044434/pexels-photo-2044434.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Hawaii, USA" => "https://images.pexels.com/photos/2521620/pexels-photo-2521620.jpeg?auto=compress&cs=tinysrgb&w=600",

            "Great Barrier Reef, Australia" => "https://images.pexels.com/photos/26447294/pexels-photo-26447294/free-photo-of-heart-reef.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Red Sea, Egypt" => "https://i.pinimg.com/736x/af/54/22/af5422b2f1a1d62ff12c1157851bb625.jpg",
            "Komodo Island, Indonesia" => "https://images.pexels.com/photos/3119775/pexels-photo-3119775.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Havelock Island, India" => "https://i.pinimg.com/474x/16/b6/d1/16b6d10e660f5849f6a4f9aa86046e59.jpg",
            "Netrani Island, India" => "https://i.pinimg.com/736x/c9/75/3e/c9753ea4ccbc7a1dd0ee2ac85972f154.jpg",
            "Lakshadweep, India" => "https://i.pinimg.com/736x/43/67/11/43671105f7cb607acb5f47ea4e93df32.jpg",

            "London, UK" => "https://images.pexels.com/photos/672532/pexels-photo-672532.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Berlin, Germany" => "https://images.pexels.com/photos/2570063/pexels-photo-2570063.jpeg?auto=compress&cs=tinysrgb&w=600",
            "New York City, USA" => "https://i.pinimg.com/736x/fe/76/02/fe76021a101f7ae6103d72e65cda437e.jpg",

            "Agra, India" => "https://images.pexels.com/photos/1603650/pexels-photo-1603650.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Hampi, India" => "https://images.pexels.com/photos/3936815/pexels-photo-3936815.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Madurai, India" => "https://images.pexels.com/photos/10710416/pexels-photo-10710416.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Khajuraho, India" => "https://images.pexels.com/photos/7184626/pexels-photo-7184626.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Gwalior, India" => "https://images.pexels.com/photos/5949485/pexels-photo-5949485.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Orchha, India" => "https://images.pexels.com/photos/20000868/pexels-photo-20000868/free-photo-of-ruins-of-raja-mahal-in-orchha.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Mysore, India" => "https://images.pexels.com/photos/4134644/pexels-photo-4134644.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
            "Cairo, Egypt" => "https://images.pexels.com/photos/31589053/pexels-photo-31589053/free-photo-of-vibrant-middle-eastern-bazaar-scene.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Athens, Greece" => "https://images.pexels.com/photos/31588305/pexels-photo-31588305/free-photo-of-historic-parthenon-columns-under-clear-blue-sky.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Angkor Wat, Cambodia" => "https://images.pexels.com/photos/5769435/pexels-photo-5769435.jpeg?auto=compress&cs=tinysrgb&w=600",

            "Ooty, India" => "https://images.pexels.com/photos/30088193/pexels-photo-30088193/free-photo-of-lush-green-terraced-fields-in-ooty-india.jpeg?auto=compress&cs=tinysrgb&w=600https://images.pexels.com/photos/30088193/pexels-photo-30088193/free-photo-of-lush-green-terraced-fields-in-ooty-india.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Nainital, India" => "https://i.pinimg.com/474x/ca/ee/11/caee11f3d7c9029cb858a9525305f15b.jpg",
            "Munnar, India" => "https://images.pexels.com/photos/1065753/pexels-photo-1065753.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Coorg, India" => "https://i.pinimg.com/474x/71/00/27/710027d1ea90742068d10eca482a54c7.jpg",

            "Rameswaram, India" => "https://i.pinimg.com/474x/96/e8/78/96e8788b0be6077bc7bcbf8eb6332ac9.jpg",
            "Pondicherry, India" => "https://i.pinimg.com/736x/92/ce/5e/92ce5e440f3d6b1619c8cae7813b4577.jpg",

            "Boracay, Philippines" => "https://i.pinimg.com/736x/dd/15/c9/dd15c9d8830348918144c2cd07beced0.jpg",
            "Phuket, Thailand" => "https://images.pexels.com/photos/358229/pexels-photo-358229.jpeg?auto=compress&cs=tinysrgb&w=600",

            "Kyoto, Japan" => "https://images.pexels.com/photos/7526805/pexels-photo-7526805.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Petra, Jordan" => "https://images.pexels.com/photos/4388165/pexels-photo-4388165.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
            "Florence, Italy" => "https://images.pexels.com/photos/2422461/pexels-photo-2422461.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Udaipur, India" => "https://images.pexels.com/photos/1719173/pexels-photo-1719173.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
            "Thanjavur, India" => "https://images.pexels.com/photos/8230166/pexels-photo-8230166.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",

            // Fallback destinations
            "Oslo, Norway" => "https://i.pinimg.com/736x/45/61/14/4561149d0b0fcef030838f4ad475df36.jpg",
            "Zurich, Switzerland" => "https://i.pinimg.com/736x/ed/09/45/ed0945bee594d5eda724b01451e5b43a.jpg",
            "Reykjavík, Iceland" => "https://i.pinimg.com/736x/d8/02/22/d802227723c0d8a75707ec3eb9871931.jpg",
            "Gulmarg, India" => "https://images.pexels.com/photos/31569983/pexels-photo-31569983/free-photo-of-gulmarg-gondola-cable-car-in-winter-snow.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
            "Auli, India" => "https://images.pexels.com/photos/9963746/pexels-photo-9963746.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
            "Pahalgam, India" => "https://images.pexels.com/photos/25786712/pexels-photo-25786712/free-photo-of-scenic-view-of-a-green-valley-and-snowcapped-mountains-in-the-distance.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
            "Bali, Indonesia" => "https://images.pexels.com/photos/2166553/pexels-photo-2166553.jpeg?auto=compress&cs=tinysrgb&w=600",
            "Mauritius" => "https://images.pexels.com/photos/1189479/pexels-photo-1189479.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
            "Pokhara, Nepal" => "https://images.pexels.com/photos/1482822/pexels-photo-1482822.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2",
            "Aamby Valley, India" => "https://i.pinimg.com/736x/c1/65/36/c16536a8e192ca1352472593113b3f23.jpg",
            "Kolkata, India" => "https://i.pinimg.com/736x/b6/d2/28/b6d228c1138b61d898872c9570e19e84.jpg",
            "Bangalore, India" => "https://i.pinimg.com/736x/f6/e0/38/f6e038cf47ea575d949d28b09501f5c6.jpg",
            "Ahmedabad, India" => "https://i.pinimg.com/736x/67/5a/e0/675ae0f24ecf7b1d98fb45847f12f2f7.jpg"
          ];


          $wikiPages = [
            "Rishikesh, India" => "Rishikesh",
            "Leh-Ladakh, India" => "Ladakh",
            "Kasol, India" => "Kasol",
            "Spiti Valley, India" => "Spiti_Valley",
            "Ziro Valley, India" => "Ziro",

            "Maldives" => "Maldives",
            "Bora Bora" => "Bora_Bora",
            "Seychelles" => "Seychelles",
            "Goa, India" => "Goa",
            "Andaman Islands, India" => "Andaman_and_Nicobar_Islands",
            "Varkala, India" => "Varkala",
            "Kovalam, India" => "Kovalam",

            "Paris, France" => "Paris",
            "Rome, Italy" => "Rome",
            "Istanbul, Turkey" => "Istanbul",
            "Bangkok, Thailand" => "Bangkok",

            "Delhi, India" => "Delhi",
            "Mumbai, India" => "Mumbai",
            "Jaipur, India" => "Jaipur",
            "Hyderabad, India" => "Hyderabad",

            "Triund, Himachal" => "Triund",
            "Chopta, Uttarakhand" => "Chopta",
            "Tirthan Valley, India" => "Tirthan_Valley",
            "Chembra Peak, Kerala" => "Chembra_Peak",
            "Ella, Sri Lanka" => "Ella%2C_Sri_Lanka",

            "Valley of Flowers, India" => "Valley_of_Flowers_National_Park",
            "Har Ki Dun, India" => "Har_ki_Dun",
            "Kedarkantha, India" => "Kedarkantha",
            "Langtang, Nepal" => "Langtang_Valley",
            "Annapurna Base Camp, Nepal" => "Annapurna_Sanctuary",
            "Everest Base Camp, Nepal" => "Everest_Base_Camp",
            "Tiger's Nest, Bhutan" => "Paro_Taktsang",

            "Interlaken, Switzerland" => "Interlaken",
            "Dubai, UAE" => "Dubai",
            "Hawaii, USA" => "Hawaii",

            "Great Barrier Reef, Australia" => "Great_Barrier_Reef",
            "Red Sea, Egypt" => "Red_Sea",
            "Komodo Island, Indonesia" => "Komodo",
            "Havelock Island, India" => "Swaraj_Dweep",
            "Netrani Island, India" => "Netrani_Island",
            "Lakshadweep, India" => "Lakshadweep",

            "London, UK" => "London",
            "Berlin, Germany" => "Berlin",
            "New York City, USA" => "New_York_City",

            "Agra, India" => "Agra",
            "Hampi, India" => "Hampi",
            "Madurai, India" => "Madurai",
            "Khajuraho, India" => "Khajuraho",
            "Gwalior, India" => "Gwalior",
            "Orchha, India" => "Orchha",
            "Mysore, India" => "Mysore",
            "Cairo, Egypt" => "Cairo",
            "Athens, Greece" => "Athens",
            "Angkor Wat, Cambodia" => "Angkor_Wat",

            "Ooty, India" => "Ooty",
            "Nainital, India" => "Nainital",
            "Munnar, India" => "Munnar",
            "Coorg, India" => "Kodagu",

            "Rameswaram, India" => "Rameswaram",
            "Pondicherry, India" => "Pondicherry",

            "Boracay, Philippines" => "Boracay",
            "Phuket, Thailand" => "Phuket",

            "Kyoto, Japan" => "Kyoto",
            "Petra, Jordan" => "Petra",
            "Florence, Italy" => "Florence",
            "Udaipur, India" => "Udaipur",
            "Thanjavur, India" => "Thanjavur",

            "Oslo, Norway" => "Oslo",
            "Zurich, Switzerland" => "Zurich",
            "Reykjavík, Iceland" => "Reykjavik",

            "Gulmarg, India" => "Gulmarg",
            "Auli, India" => "Auli,_Uttarakhand",
            "Pahalgam, India" => "Pahalgam",
            "Bali, Indonesia" => "Bali",
            "Mauritius" => "Mauritius",
            "Pokhara, Nepal" => "Pokhara",
            "Aamby Valley, India" => "Aamby_Valley_City",
            "Kolkata, India" => "Kolkata",
            "Bangalore, India" => "Bangalore",
            "Ahmedabad, India" => "Ahmedabad"
          ];

          echo '<div class="recommendation-scroll">';
          foreach ($recs as $dest) {
            $img = isset($destinationImages[$dest]) ? $destinationImages[$dest] : 'https://via.placeholder.com/300x200?text=No+Image';

            // Check if $img is a full URL or just a filename
            $imgSrc = (strpos($img, 'http') === 0) ? $img : 'images/' . $img;

            // Use mapped Wikipedia page title if available
            $wikiTitle = isset($wikiPages[$dest])
              ? $wikiPages[$dest]
              : str_replace(" ", "_", preg_replace("/,/", "", $dest));

            echo '
    <div class="recommendation-card">
        <img src="' . $imgSrc . '" alt="' . htmlspecialchars($dest) . '">
        <p><a href="https://en.wikipedia.org/wiki/' . $wikiTitle . '" target="_blank">' . htmlspecialchars($dest) . '</a></p>
    </div>';
          }
          echo '</div>';

          ?>
        </ul>
      </div>
    <?php endif; ?>
  </div>

  <footer>
    <p>&copy; 2024 TravelMate. All rights reserved.</p>
  </footer>
</body>

</html>
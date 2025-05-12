var data = [];
var filteredData = [];

var plan = [];

function loadData(filters = {}) {
  const queryParams = new URLSearchParams(filters).toString();
  fetch(`get_weather_data.php?${queryParams}`)
    .then((res) => res.json())
    .then((fetchedData) => {
      data = fetchedData;
      filteredData = data;
      renderData(filteredData);
      renderPlanData(plan);
    })
    .catch((error) => console.error("Error loading data:", error));
}

function renderData(data) {
  var tHead = document.querySelector("#weather_data thead");
  var tbody = document.querySelector("#weather_data tbody");
  tHead.innerHTML = ""; // Clear existing header
  tbody.innerHTML = ""; // Clear existing rows
  data.forEach((row, index) => {
    if (index == 0) {
      // Create header row
      var tr = document.createElement("tr");
      Object.keys(row).forEach((key) => {
        var th = document.createElement("th");
        th.textContent = key;
        tr.appendChild(th);
      });
      tr.appendChild(document.createElement("th")); // Empty cell for "Add" button
      tHead.appendChild(tr);
    }
    var tr = document.createElement("tr");
    Object.values(row).forEach((value) => {
      var td = document.createElement("td");
      td.textContent = value;
      tr.appendChild(td);
    });
    var td = document.createElement("td");
    var button = document.createElement("button");
    if (plan.find((item) => item["Date"] === row["Date"])) {
      button.className = "remove-button";
      button.textContent = "Remove";
      button.onclick = function () {
        onRemove(row["Date"]);
      };
    } else {
      button.textContent = "Add";
      button.onclick = function () {
        onAdd(row["Date"]);
      };
    }
    button.style.width = "100%";
    td.appendChild(button);
    tr.appendChild(td);
    tbody.appendChild(tr);
  });
}

function onFilter() {
  // Get weather type
  const weatherType = document.getElementById("weatherType").value;

  // Get minimum temperature
  const minTemp = document.getElementById("minTemp").value.trim();

  // Get selected seasons
  const seasons = ["spring", "summer", "autumn"].filter((season) => {
    const checkbox = document.getElementById(season);
    return checkbox.checked;
  });

  // Prepare filter object
  const filters = { weatherType, minTemp, seasons };

  loadData(filters);
}

function onAdd(datum) {
  var planItem = data.find((item) => item["Date"] === datum);
  plan.push(planItem);
  console.log(plan);
  renderData(filteredData);
  renderPlanData(plan);
}

function onRemove(datum) {
  var planItem = plan.find((item) => item["Date"] === datum);
  if (planItem) {
    plan = plan.filter((item) => item["Date"] !== datum);
  }
  console.log(plan);
  renderData(filteredData);
  renderPlanData(plan);
}

function renderPlanData(planItems) {
  const button = document.getElementById("send_button");
  if (planItems.length > 0) {
    button.style.display = "block";
  } else {
    button.style.display = "none";
  }

  const planList = document.getElementById("plan_list");
  planList.innerHTML = "";

  planItems.forEach((item) => {
    const card = document.createElement("div");
    card.className = "plancard";
    card.style.cssText =
      "border: 1px solid black; border-radius: 10px; padding: 5px";

    const info = `
            <div class="plancard-info" style="margin-bottom: 3px">
                <div>
                    <h3>Datum: ${item.Date}</h3>
                    <p>Temperatura: ${item.Temperature}°C</p>
                    <p>Vlažnost: ${item.Humidity}%</p>
                    <p>Brzina vjetra: ${item["Wind Speed"]} km/h</p>
                    <p>Oblačnost: ${item["Cloud Cover"]}%</p>
                    <p>Stanje: ${item["Weather Type"]}</p>
                    <p>Sezona: ${item.Season}</p>
                </div>
                <button
                    style="background-color: red; width: 100%"
                    onclick="onRemove('${item.Date}')"
                >
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        `;

    card.innerHTML = info;
    planList.appendChild(card);
  });
}

function sendPlans() {
  plan.forEach((item) => {
    onRemove(item.Date);
  });
}

loadData();

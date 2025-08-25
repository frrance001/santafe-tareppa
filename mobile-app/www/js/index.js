document.addEventListener("deviceready", function() {
    fetch("http://127.0.0.1:8000/api/rides", {
        headers: { "Authorization": "Bearer YOUR_TOKEN" }
    })
    .then(res => res.json())
    .then(data => {
        console.log("Rides from Laravel:", data);
        document.getElementById("rides").innerHTML = JSON.stringify(data);
    })
    .catch(err => console.error(err));
});

document.addEventListener('DOMContentLoaded', () => {
    const dest = document.getElementById('search-destination');
    const ci   = document.getElementById('check-in-date');
    const co   = document.getElementById('check-out-date');
    const g    = document.getElementById('guest-count');
    const btn  = document.querySelector('.search-button');
    const container = document.querySelector('.properties-section');
  
    btn.addEventListener('click', async () => {
      const url = new URL('/V_Resorts/WEBSITE%20REVAMPED/webservice/api.php', location.origin);
      url.searchParams.set('action','properties');
      if (dest.value) url.searchParams.set('destination', dest.value.trim());
      if (ci.value)   url.searchParams.set('check_in',   ci.value);
      if (co.value)   url.searchParams.set('check_out',  co.value);
      if (g.value)    url.searchParams.set('guests',     g.value.trim());
  
      try {
        const resp = await fetch(url);
        const data = await resp.json();
        renderProperties(data);
      } catch (err) {
        container.innerHTML = '<p class="error">Could not load properties.</p>';
        console.error(err);
      }
    });
  
    function renderProperties(props) {
      container.innerHTML = props.map(p=>`
        <div class="propertyCard">
          <img src="data:image/jpeg;base64,${p.propertyPhoto}" alt="${p.Name}">
          <h3>${p.Name}</h3>
          <p>${p.Description}</p>
          <p><strong>Price:</strong> $${p.Price.toLocaleString()} per night</p>
          <a class="bookNowButton"
             href="/V_Resorts/WEBSITE%20REVAMPED/Pages/SpecificPropertyPage/SpecificPropertyPage.php?property_id=${p.Property_ID}">
            Book Now
          </a>
        </div>
      `).join('');
    }
  });
  
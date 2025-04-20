document.addEventListener("DOMContentLoaded", () => {
  const calEl = document.getElementById("calendar");
  if (!calEl || typeof flatpickr === "undefined") return;

  const price        = parseFloat(calEl.dataset.price) || 0;
  const disabled     = JSON.parse(calEl.dataset.disabled || "[]");
  const ciInput      = document.getElementById("checkInInput");
  const coInput      = document.getElementById("checkOutInput");
  const ciDisplay    = document.getElementById("ciDisplay");
  const coDisplay    = document.getElementById("coDisplay");
  const nightsDisp   = document.getElementById("nightsDisplay");
  const subtotalDisp = document.getElementById("subtotalDisplay");
  const clearBtn     = document.querySelector(".clear-dates");

  const fp = flatpickr(calEl, {
    mode: "range",
    inline: true,
    dateFormat: "Y-m-d",
    minDate: "today",
    disable: disabled,  // <— now an array of {from,to} objects
    onChange: selectedDates => {
      if (selectedDates.length === 2) {
        const [start, end] = selectedDates;
        const nights = Math.round((end - start) / (1000*60*60*24));
        ciInput.value = fp.formatDate(start, "Y-m-d");
        coInput.value = fp.formatDate(end,   "Y-m-d");
        ciDisplay.textContent    = fp.formatDate(start, "M j, Y");
        coDisplay.textContent    = fp.formatDate(end,   "M j, Y");
        nightsDisp.textContent   = nights;
        subtotalDisp.textContent = "Rs. " + (nights * price).toLocaleString();
      }
    }
  });

  clearBtn.addEventListener("click", () => {
    fp.clear();
    ciInput.value = coInput.value = "";
    ciDisplay.textContent = coDisplay.textContent = "—";
    nightsDisp.textContent    = "0";
    subtotalDisp.textContent  = "Rs. 0";
  });
});

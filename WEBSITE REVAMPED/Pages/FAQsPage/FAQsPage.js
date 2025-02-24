document.addEventListener('DOMContentLoaded', () => {
    const faqItems = document.querySelectorAll('.faq-item');
  
    faqItems.forEach(item => {
      item.addEventListener('click', () => {
        const answer = item.querySelector('.faq-answer');
        const toggleSign = item.querySelector('.faq-toggle-sign');
  
        // Check if the clicked FAQ item is already expanded
        const isOpen = answer.style.display === 'block';
  
        // Close all answers and reset the + sign
        faqItems.forEach(i => {
          const otherAnswer = i.querySelector('.faq-answer');
          const otherToggleSign = i.querySelector('.faq-toggle-sign');
          otherAnswer.style.display = 'none';
          otherToggleSign.textContent = '+';
        });
  
        // If the clicked item wasn't already open, open it
        if (!isOpen) {
          answer.style.display = 'block';
          toggleSign.textContent = '-';
        } else {
          // If it was open, close it
          answer.style.display = 'none';
          toggleSign.textContent = '+';
        }
      });
    });
  });
  
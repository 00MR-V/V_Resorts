document.addEventListener('DOMContentLoaded', () => {
    const faqItems = document.querySelectorAll('.faq-item');
  
    faqItems.forEach(item => {
      item.addEventListener('click', () => {
        const answer = item.querySelector('.faq-answer');
        const toggleSign = item.querySelector('.faq-toggle-sign');
  
       
        const isOpen = answer.style.display === 'block';
  
        
        faqItems.forEach(i => {
          const otherAnswer = i.querySelector('.faq-answer');
          const otherToggleSign = i.querySelector('.faq-toggle-sign');
          otherAnswer.style.display = 'none';
          otherToggleSign.textContent = '+';
        });
  
        if (!isOpen) {
          answer.style.display = 'block';
          toggleSign.textContent = '-';
        } else {
        
          answer.style.display = 'none';
          toggleSign.textContent = '+';
        }
      });
    });
  });
  
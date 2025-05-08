function validerForm() {
    const form = document.forms["forminv"];
    let isValid = true;
  
    // Réinitialiser les styles
    for (const element of form.elements) {
      element.style.border = "";
    }
   
      // Date inv
      if (form["date_inv"].value === "") {
        form["date_inv"].style.border = "2px solid red";
        alert("Veuillez entrer une date d'investissement.");
        isValid = false;
        return isValid;
      }
      const selectedDate = new Date(form["date_inv"].value);
      const today = new Date();
      
      // Mettre à 00:00:00 pour ne pas comparer les heures
      today.setHours(0, 0, 0, 0);
      
      if (selectedDate <= today) {
          form["date_inv"].style.border = "2px solid red";
          alert("La date d'investissement doit être ultérieure à aujourd'hui.");
          isValid = false;
          return isValid;
      }
   
    // Type inv
    if (form["type_paiement"].value.trim() === "") {
      form["type_paiement"].style.border = "2px solid red";
      alert("Veuillez entrer un type de paiement.");
      isValid = false;
      return isValid;
    }
  
  
     // Devise
     if (form["devise"].value.trim() === "") {
        form["devise"].style.border = "2px solid red";
        alert("Veuillez entrer un devise.");
        isValid = false;
        return isValid;
      }
  }
  
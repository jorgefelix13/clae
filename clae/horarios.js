function generarHorarios() {
    const diaSemana = new Date(document.querySelector('input[name="REC_Fecha"]').value).getDay(); 
    const areaSeleccionada = document.querySelector('select[name="REC_Area"]').value;

    const horariosDisponibles = {
        Nutricion: {
            default: [
                "07:00", "07:30", "08:00", "08:30", "09:00", "09:30", 
                "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", 
                "13:00", "13:30", "14:00", "14:30", "15:00", "15:30", 
                "16:00", "16:30", "17:00", "17:30", "18:00", "18:30", "19:00"
            ]
        },
        Medicina: {
            default: [
                "07:00", "07:30", "08:00", "08:30", "09:00", "09:30", 
                "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", 
                "13:00", "13:30", "14:00", "14:30", "15:00", "15:30", 
                "16:00", "16:30", "17:00", "17:30", "18:00", "18:30", "19:00"
            ]
        },
        Psicologia: {
            default: [
                "07:00", "07:30", "08:00", "08:30", "09:00", "09:30", 
                "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", 
                "13:00", "13:30", "14:00", "14:30", "15:00", "15:30", 
                "16:00", "16:30", "17:00", "17:30", "18:00", "18:30", "19:00"
            ]
        }
    };

    const selectHorarios = document.getElementById('REC_Horario');
    selectHorarios.innerHTML = ""; 

    const horarios =
        (horariosDisponibles[areaSeleccionada] &&
         horariosDisponibles[areaSeleccionada][diaSemana]) ||
        horariosDisponibles[areaSeleccionada]?.default || [];

    horarios.forEach(hora => {
        const option = document.createElement('option');
        option.value = hora;
        option.textContent = hora;
        selectHorarios.appendChild(option);
    });
}

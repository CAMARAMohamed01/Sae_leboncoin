@extends('layouts.app')
@section('content')
    <h1>vous voulez reserver l'annonce : {{$annonce->titreannonce}}</h1>




    <p class="text-lbc-orange font-bold mt-1" id="prixloc">{{ $annonce->prixPeriodes->prix }}</p>
    <p>date arriver</p>
    <input type="date" id="inputdatearriver" class="dateenter" value="1" >
    <p>date de depart</p>
    <input type="date" id="inputdatedepart" class="dateenter" value="1" >
    <p id="presultat"></p>
    <input type="number" value="{{ $annonce->prixPeriodes->prix }}" id="prixinput">

    <style>
        #prixinput{
            visibility : hidden ;
        }
    </style>
    <script>
        const prixfinal = 0 ;
        const prixfinalp = document.querySelector("#presultat")
        const nnb = document.querySelector("#inputdatearriver")
        const nnb2 = document.querySelector("#inputdatedepart")
        const nnb3 = document.querySelectorAll(".dateenter")
        const prixfixe = document.querySelector("#prixinput").value  
        var res = 0
        nnb3.forEach(element => {
            function formaterDate(date) {
        const annee = date.getFullYear();
        const mois = String(date.getMonth() + 1).padStart(2, '0');
        const jour = String(date.getDate()).padStart(2, '0');
        return `${annee}-${mois}-${jour}`;
    }
        
        if(element) {
        const aujourdhui = new Date();
        element.min = formaterDate(aujourdhui); 
        const dateDansDeuxAns = new Date(aujourdhui);
        dateDansDeuxAns.setFullYear(aujourdhui.getFullYear() + 2);
        element.max = formaterDate(dateDansDeuxAns);
    }
    
            element.addEventListener('change', function(event) {
                if(nnb.value == null || nnb.value == ""){
                    prixfinalp.innerText = "inserer une valeur dans une date d'entrer"
                }
                else if(nnb2.value == null || nnb2.value == ""){
                    prixfinalp.innerText = "inserer une valeur dans une date de fin "
                }
                else if (nnb2.value < nnb.value){
                    prixfinalp.innerText = "la date de sortie doit etre apres la date d'entrer "
                }
                else{
                    date = new Date(nnb.value)
                date2 = new Date(nnb2.value)
                const jours = (date2 - date) / (1000 * 60 * 60 * 24);
                const prix =  prixfixe * jours
                prixfinalp.innerText = "prix total de la reservation  " +prix + " €"
                }
        
                // date = new Date(nnb.value)
                // date2 = new Date(nnb2.value)
                // const jours = (date2 - date) / (1000 * 60 * 60 * 24);
                // const prix =  prixfixe * jours
                // prixfinalp.innerText = "prix total de la reservation  " +prix + " €"
                // const joursRestants = Math.ceil(differenceTemps / (1000 * 60 * 60 * 24));
                // alert(joursRestants)
                
               

            });

        })
        
       
        

        


    </script>

        
@endsection
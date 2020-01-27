# Domande e risposte - gestione alloggi
D: **Come funziona l'hosting interno messo a disposizione dalla scuola?**  
R: *Hosting da vedere in futuro*

D: **Come si fa a distinguere, durante una registrazione, un utente normale da un amministratore o da un amministratore gerente?**  
R: *Durante le registrazioni si possono registrare solo utenti normali e amministratori gerenti. Non è possibile registrare un amministratore.*

D: **Oltre alla registrazione, deve anche esserci la possibilità di un login?**  
R: *Sì.*

D: **Cosa intende con "Meccanismo di concorrenzialità"? Come funziona?**  
R: *Si intende un meccanismo che faccia sì che due persone non possono riservare lo stesso alloggio contemporaneamente, per farlo si può usare un framework.*

D: **Cosa intende con "DVD per tutti i file del progetto"?**  
R: *Alla fine del progetto tutti i file del progetto vanno caricati su un DVD e poi consegnati al responsabile.*

D: **Come vengono gestiti tutti gli alloggi? Devo inserirli tutti a mano? Se si quanti? Vanno inseriti in un Database? Se serve un database per gli alloggi, va separato da quello per gestire la fatturazione?**

R: *Vai a creare uno script che ti carichi in automatico gli alloggi, direi almeno una ventina. Lo script ti serve anche per fare un reset dei dati al fronte di errori di codice che ti "sporcano il DB".*

D: **Va progettato anche quello? O unisco tutto in un singolo Databse?**  

R: *Nel tuo DB manca in effetti la tabella Fattura che devi unire agli utenti, metti tutto in un DB unico. La relazione che hai messo tra Amministratore e Alloggio non serve e deve essere tolta, ma ti serve una relazione tra l'Amministratore e l'Ammionistratore gerente. Se hai delle domande domani sono in sede a partire dalle 10:05. Saluti GM*



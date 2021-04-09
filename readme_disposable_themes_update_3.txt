PHPVMS v7 Themes : Disposable & Disposable SideBar v1.02 UPDATE #3

* Installation
  If you have a default install of phpvsm just unzip the file at root folder of your phpvms install.
  Or just copy/upload the files manually over your files, accept overwrite when asked.

* SimBrief Aircraft Selection (flights/simbrief_aircraft.blade.php)
  Switched from Subfleets->Aircrafts to Aircrafts as phpvms core.
  Aircraft list is generated in controller according to your phpvms settings.

* SimBrief Form (flights/simbrief_form.blade.php)
  Added Cruise Fuel Policy selection (CI: Cost Index / LRC: Long Range Cruise)
    CI value can be manually entered (if pilot or VA wants to use a fixed CI instead of AUTO)
    If no Fligt Time is defined for the flight, CI will be disabled by default
    Also switching to LRC will disable CI Value field to be disabled
  Improved the StepClimb selection script
    If no Flight Level is defined, StepClimb will be enabled
    If pilot wants a fixed flight level and enter a FL then StepClimb will be disabled
  Added Briefing Format selection
    Defined some airlines as an example, LIDO is selected by default
    If you need any other formats check SimBrief website and edit the options as you wish

* Flights 
  Changed the logic for displaying button for viewing SimBrief OFP if there is one
  If there is a generated OFP by the user, generate buton will not be displayed

Safe flights and enjoy.
B.Fatih KOZ
'Disposable Hero'
https://github.com/FatihKoz
24.MAR.21
<?php

/**
 * Example module language file
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
$lang = [
    'HEADLINE' => 'Umfragen verwalten',
    'GUI_ADD_POLL' => 'Umfragen erstellen',
    'GUI_CLOSE_POLL' => 'Umfragen schließen',
    'GUI_ADD_REPLY' => 'Antwort hinzufügen',
    'GUI_GOTO_LIST' => 'zur Übersicht',

    'GUI_POLL' => 'Umfrage',
    'GUI_REPLIES' => 'Antworten',
    'GUI_RESULT' => 'Übersicht',
    'GUI_VOTESLIST' => 'Stimmenübersicht',

    'GUI_POLL_TEXT' => 'Frage',
    'GUI_POLL_TIMESPAN' => 'Zeitraum',
    'GUI_POLL_STATE' => 'Status',
    'GUI_POLL_VOTES' => 'Stimmen',
    'GUI_POLL_MAXVOTES' => 'Anzahl wählbarer Antworten',
    'GUI_POLL_START' => 'Beginn',
    'GUI_POLL_STOP' => 'Ende',
    'GUI_POLL_COOKIE' => 'Erneute Abstimmung nach X Sekunden ',
    'GUI_POLL_COOKIE_DEFAULT' => 'Erneute Abstimmung standardmäßig nach X Sekunden ',
    'GUI_POLL_ISCLOSED' => 'Umfrage geschlossen',
    'GUI_POLL_INARCHIVE' => 'Umfrage in Archiv anzeigen',
    'GUI_POLL_REPLY_TXT' => 'Antwort {{id}}',
    'GUI_POLL_REPLYSAVED' => 'Abgestimmt um',
    'GUI_POLL_VOTELOG_REPLY_NOTFOUND' => 'Antwort mit ID "{{replyId}}" nicht gefunden',

    'GUI_DASHBOARD_LATEST' => 'Umfrage: {{text}}',

    'GUI_CONFIG_SHOWLATEST' => 'Standardmäßig aktuellste Umfrage anzeigen',
    'GUI_CONFIG_CHARTTYPE' => 'Diagram-Typ',
    
    'GUI_PUB_SUBMITVOTE' => 'Abstimmen',
    'GUI_PUB_SHOWRESULTS' => 'Ergebnisse anzeigen',
    'GUI_PUB_SHOWPOLL' => 'Umfrage anzeigen',

    'POLL_STATUS_ALL' => 'Alle Umfragen anzeigen',
    'POLL_STATUS0' => 'Offen',
    'POLL_STATUS1' => 'Geschlossen',
    
    'GUI_CHARTTYPE_BAR' => 'Balkendiagramm',
    'GUI_CHARTTYPE_PIE' => 'Tortendiagramm',
    'GUI_CHARTTYPE_DOUGHNUT' => 'Ringdiagramm',
    
    'MSG_SUCCESS_SAVEPOLL' => 'Die Umfrage wurden gespeichert!',
    'MSG_PUB_NOTFOUND' => 'Die ausgewählte Umfrage wurde nicht gefunden',
    'MSG_PUB_NOARCHIVE' => 'Es wurden keine archivierten Umfragen gefunden.',
    'MSG_PUB_SUCCESS_REPLY' => 'Vielen Dank für deine Antwort!',
    'MSG_PUB_ERRCODE_GEN' => 'Beim Sender der Anfrage ist ein Fehler aufgetreten.',
    'MSG_PUB_ERRCODE_REPLY' => 'Die Antwort konnte nicht gespeichert werden, bitte versuche es später noch einmal.',
    'MSG_PUB_ERRCODE_POLL' => 'Diese Umfrage wurde nicht gefunden.',
    'MSG_PUB_ERRCODE_CLOSED' => 'Die ausgewählte Umfrage wurde geschlossen oder beendet.',
    'MSG_ERR_INSERTDATA' => 'Bitte fülle das Formular zum Erstellen der Umfrage aus!',
    'MSG_ERR_SAVEPOLL' => 'Fehler beim Speichern der Umfrage!',
    'MSG_ERR_SAVEREPLY' => 'Fehler beim Speichern der Antworten!',
    'MSG_ERR_UPDATEREPLY' => 'Fehler beim Aktualisieren der Antworten!',
    'MSG_ERR_UPDATEPOLL' => 'Fehler beim Speichern von Änderungen der Umfrage!',
    'MSG_ERR_DELETEPOLL' => 'Die gewählte Umfrage konnte nicht gelöscht werden!',
    'MSG_ERR_CLOSEPOLL' => 'Die gewählte Umfrage konnte nicht geschlossen werden!',
    
    'CRONJOB_ANONYMIZEVOTELOG' => 'Stimmenübersicht anonymisieren',
    'SYSCHECK_FOLDER_MODULE_NKORGPOLLS' => 'Umfrage-Templates',
    'SYSCHECK_FOLDER_NKORG_POLLS' => 'Umfrage-Templates'

];

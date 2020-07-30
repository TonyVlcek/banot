import $ from 'jquery';
import 'bootstrap';

//// Assets
import './styles/main.scss';
import '@fortawesome/fontawesome-free/css/all.css';

// UI tweaks
import './ui/naja';
import './ui/netteForms';

// Modules
import greet from "./greet";

$(document).ready(function() {
	console.log(greet('jill'));
});

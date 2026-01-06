import "./libs/trix";
import './bootstrap';
import "flowbite";
import { DataTable } from "simple-datatables";
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'
 
Alpine.plugin(collapse)
window.Alpine = Alpine;
window.DataTable = DataTable;
Alpine.start();


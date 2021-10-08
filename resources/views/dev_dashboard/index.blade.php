@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    {{-- <h1>Dashboard</h1> --}}
@stop

@section('content')
<style type="text/css">

.fa-spin-fast {
  -webkit-animation: fa-spin-fast 0.2s infinite linear;
  animation: fa-spin-fast 0.2s infinite linear;
}
@-webkit-keyframes fa-spin-fast {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(359deg);
    transform: rotate(359deg);
  }
}
@keyframes fa-spin-fast {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(359deg);
    transform: rotate(359deg);
  }
}
.material-card {
  position: relative;
  height: 0;
  padding-bottom: calc(100% - 16px);
  margin-bottom: 6.6em;
}
.material-card h2 {
  position: absolute;
  top: calc(100% - 16px);
  left: 0;
  width: 100%;
  padding: 10px 16px;
  color: #fff;
  font-size: 1.4em;
  line-height: 1.6em;
  margin: 0;
  z-index: 10;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -ms-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
}
.material-card h2 span {
  display: block;
}
.material-card h2 strong {
  font-weight: 400;
  display: block;
  font-size: 0.8em;
}
.material-card h2:before,
.material-card h2:after {
  content: ' ';
  position: absolute;
  left: 0;
  top: -16px;
  width: 0;
  border: 8px solid;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -ms-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
}
.material-card h2:after {
  top: auto;
  bottom: 0;
}
@media screen and (max-width: 767px) {
  .material-card.mc-active {
    padding-bottom: 0;
    height: auto;
  }
}
.material-card.mc-active h2 {
  top: 0;
  padding: 10px 16px 10px 90px;
}
.material-card.mc-active h2:before {
  top: 0;
}
.material-card.mc-active h2:after {
  bottom: -16px;
}
.material-card .mc-content {
  position: absolute;
  right: 0;
  top: 0;
  bottom: 16px;
  left: 16px;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -ms-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
}
.material-card .mc-btn-action {
  position: absolute;
  right: 16px;
  top: 15px;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  border-radius: 50%;
  border: 5px solid;
  width: 54px;
  height: 54px;
  line-height: 44px;
  text-align: center;
  color: #fff;
  cursor: pointer;
  z-index: 20;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -ms-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
}
.material-card.mc-active .mc-btn-action {
  top: 62px;
}
.material-card .mc-description {
  position: absolute;
  top: 100%;
  right: 30px;
  left: 30px;
  bottom: 54px;
  overflow: hidden;
  opacity: 0;
  filter: alpha(opacity=0);
  -webkit-transition: all 1.2s;
  -moz-transition: all 1.2s;
  -ms-transition: all 1.2s;
  -o-transition: all 1.2s;
  transition: all 1.2s;
}
.material-card .mc-footer {
  height: 0;
  overflow: hidden;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -ms-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
}
.material-card .mc-footer h4 {
  position: absolute;
  top: 200px;
  left: 30px;
  padding: 0;
  margin: 0;
  font-size: 16px;
  font-weight: 700;
  -webkit-transition: all 1.4s;
  -moz-transition: all 1.4s;
  -ms-transition: all 1.4s;
  -o-transition: all 1.4s;
  transition: all 1.4s;
}
.material-card .mc-footer a {
  display: block;
  float: left;
  position: relative;
  width: 52px;
  height: 52px;
  margin-left: 5px;
  margin-bottom: 15px;
  font-size: 28px;
  color: #fff;
  line-height: 52px;
  text-decoration: none;
  top: 200px;
}
.material-card .mc-footer a:nth-child(1) {
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
  -ms-transition: all 0.5s;
  -o-transition: all 0.5s;
  transition: all 0.5s;
}
.material-card .mc-footer a:nth-child(2) {
  -webkit-transition: all 0.6s;
  -moz-transition: all 0.6s;
  -ms-transition: all 0.6s;
  -o-transition: all 0.6s;
  transition: all 0.6s;
}
.material-card .mc-footer a:nth-child(3) {
  -webkit-transition: all 0.7s;
  -moz-transition: all 0.7s;
  -ms-transition: all 0.7s;
  -o-transition: all 0.7s;
  transition: all 0.7s;
}
.material-card .mc-footer a:nth-child(4) {
  -webkit-transition: all 0.8s;
  -moz-transition: all 0.8s;
  -ms-transition: all 0.8s;
  -o-transition: all 0.8s;
  transition: all 0.8s;
}
.material-card .mc-footer a:nth-child(5) {
  -webkit-transition: all 0.9s;
  -moz-transition: all 0.9s;
  -ms-transition: all 0.9s;
  -o-transition: all 0.9s;
  transition: all 0.9s;
}
.material-card .img-container {
  overflow: hidden;
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  z-index: 3;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -ms-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
}
.material-card.mc-active .img-container {
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  border-radius: 50%;
  left: 0;
  top: 12px;
  width: 60px;
  height: 60px;
  z-index: 20;
}
.material-card.mc-active .mc-content {
  padding-top: 5.6em;
}
.material-card.mc-active .mc-content .row{
    display:none;
}
.material-card.mc-active .mc-content h1{
    display:none;
}
.material-card.mc-active .mc-content small{
    display:none;
}
@media screen and (max-width: 767px) {
  .material-card.mc-active .mc-content {
    position: relative;
    min-height: none !important;
    overflow:auto !important;
    margin-right: 16px;
  }
}
.material-card.mc-active .mc-description {
    top: 100px;
    width: 90%;
    padding-right: 2em;
    padding-top: 6.6em;
    opacity: 1;
  filter: alpha(opacity=100);
}
@media screen and (max-width: 767px) {
  .material-card.mc-active .mc-description {
    position: relative;
    top: auto;
    right: auto;
    left: auto;
    padding: 50px 30px 70px 30px;
    bottom: 0;
  }
}
.material-card.mc-active .mc-footer {
  overflow: visible;
  position: absolute;
  top: calc(100% - 16px);
  left: 16px;
  right: 0;
  height: 82px;
  padding-top: 15px;
  padding-left: 25px;
}
.material-card.mc-active .mc-footer a {
  top: 0;
}
.material-card.mc-active .mc-footer h4 {
  top: -32px;
}
.material-card.Red h2 {
  background-color: #F44336;
}
.material-card.Red h2:after {
  border-top-color: #F44336;
  border-right-color: #F44336;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Red h2:before {
  border-top-color: transparent;
  border-right-color: #B71C1C;
  border-bottom-color: #B71C1C;
  border-left-color: transparent;
}
.material-card.Red.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #F44336;
  border-bottom-color: #F44336;
  border-left-color: transparent;
}
.material-card.Red.mc-active h2:after {
  border-top-color: #B71C1C;
  border-right-color: #B71C1C;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Red .mc-btn-action {
  background-color: #F44336;
}
.material-card.Red .mc-btn-action:hover {
  background-color: #B71C1C;
}
.material-card.Red .mc-footer h4 {
  color: #B71C1C;
}
.material-card.Red .mc-footer a {
  background-color: #B71C1C;
}
.material-card.Red.mc-active .mc-content {
  background-color: #FFEBEE;
}
.material-card.Red.mc-active .mc-footer {
  background-color: #FFCDD2;
}
.material-card.Red.mc-active .mc-btn-action {
  border-color: #FFEBEE;
}
.material-card.Blue-Grey h2 {
  background-color: #607D8B;
}
.material-card.Blue-Grey h2:after {
  border-top-color: #607D8B;
  border-right-color: #607D8B;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Blue-Grey h2:before {
  border-top-color: transparent;
  border-right-color: #263238;
  border-bottom-color: #263238;
  border-left-color: transparent;
}
.material-card.Blue-Grey.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #607D8B;
  border-bottom-color: #607D8B;
  border-left-color: transparent;
}
.material-card.Blue-Grey.mc-active h2:after {
  border-top-color: #263238;
  border-right-color: #263238;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Blue-Grey .mc-btn-action {
  background-color: #607D8B;
}
.material-card.Blue-Grey .mc-btn-action:hover {
  background-color: #263238;
}
.material-card.Blue-Grey .mc-footer h4 {
  color: #263238;
}
.material-card.Blue-Grey .mc-footer a {
  background-color: #263238;
}
.material-card.Blue-Grey.mc-active .mc-content {
  background-color: #ECEFF1;
}
.material-card.Blue-Grey.mc-active .mc-footer {
  background-color: #CFD8DC;
}
.material-card.Blue-Grey.mc-active .mc-btn-action {
  border-color: #ECEFF1;
}
.material-card.Pink h2 {
  background-color: #E91E63;
}
.material-card.Pink h2:after {
  border-top-color: #E91E63;
  border-right-color: #E91E63;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Pink h2:before {
  border-top-color: transparent;
  border-right-color: #880E4F;
  border-bottom-color: #880E4F;
  border-left-color: transparent;
}
.material-card.Pink.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #E91E63;
  border-bottom-color: #E91E63;
  border-left-color: transparent;
}
.material-card.Pink.mc-active h2:after {
  border-top-color: #880E4F;
  border-right-color: #880E4F;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Pink .mc-btn-action {
  background-color: #E91E63;
}
.material-card.Pink .mc-btn-action:hover {
  background-color: #880E4F;
}
.material-card.Pink .mc-footer h4 {
  color: #880E4F;
}
.material-card.Pink .mc-footer a {
  background-color: #880E4F;
}
.material-card.Pink.mc-active .mc-content {
  background-color: #FCE4EC;
}
.material-card.Pink.mc-active .mc-footer {
  background-color: #F8BBD0;
}
.material-card.Pink.mc-active .mc-btn-action {
  border-color: #FCE4EC;
}
.material-card.Purple h2 {
  background-color: #9C27B0;
}
.material-card.Purple h2:after {
  border-top-color: #9C27B0;
  border-right-color: #9C27B0;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Purple h2:before {
  border-top-color: transparent;
  border-right-color: #4A148C;
  border-bottom-color: #4A148C;
  border-left-color: transparent;
}
.material-card.Purple.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #9C27B0;
  border-bottom-color: #9C27B0;
  border-left-color: transparent;
}
.material-card.Purple.mc-active h2:after {
  border-top-color: #4A148C;
  border-right-color: #4A148C;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Purple .mc-btn-action {
  background-color: #9C27B0;
}
.material-card.Purple .mc-btn-action:hover {
  background-color: #4A148C;
}
.material-card.Purple .mc-footer h4 {
  color: #4A148C;
}
.material-card.Purple .mc-footer a {
  background-color: #4A148C;
}
.material-card.Purple.mc-active .mc-content {
  background-color: #F3E5F5;
}
.material-card.Purple.mc-active .mc-footer {
  background-color: #E1BEE7;
}
.material-card.Purple.mc-active .mc-btn-action {
  border-color: #F3E5F5;
}
.material-card.Deep-Purple h2 {
  background-color: #673AB7;
}
.material-card.Deep-Purple h2:after {
  border-top-color: #673AB7;
  border-right-color: #673AB7;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Deep-Purple h2:before {
  border-top-color: transparent;
  border-right-color: #311B92;
  border-bottom-color: #311B92;
  border-left-color: transparent;
}
.material-card.Deep-Purple.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #673AB7;
  border-bottom-color: #673AB7;
  border-left-color: transparent;
}
.material-card.Deep-Purple.mc-active h2:after {
  border-top-color: #311B92;
  border-right-color: #311B92;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Deep-Purple .mc-btn-action {
  background-color: #673AB7;
}
.material-card.Deep-Purple .mc-btn-action:hover {
  background-color: #311B92;
}
.material-card.Deep-Purple .mc-footer h4 {
  color: #311B92;
}
.material-card.Deep-Purple .mc-footer a {
  background-color: #311B92;
}
.material-card.Deep-Purple.mc-active .mc-content {
  background-color: #EDE7F6;
}
.material-card.Deep-Purple.mc-active .mc-footer {
  background-color: #D1C4E9;
}
.material-card.Deep-Purple.mc-active .mc-btn-action {
  border-color: #EDE7F6;
}
.material-card.Indigo h2 {
  background-color: #3F51B5;
}
.material-card.Indigo h2:after {
  border-top-color: #3F51B5;
  border-right-color: #3F51B5;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Indigo h2:before {
  border-top-color: transparent;
  border-right-color: #1A237E;
  border-bottom-color: #1A237E;
  border-left-color: transparent;
}
.material-card.Indigo.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #3F51B5;
  border-bottom-color: #3F51B5;
  border-left-color: transparent;
}
.material-card.Indigo.mc-active h2:after {
  border-top-color: #1A237E;
  border-right-color: #1A237E;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Indigo .mc-btn-action {
  background-color: #3F51B5;
}
.material-card.Indigo .mc-btn-action:hover {
  background-color: #1A237E;
}
.material-card.Indigo .mc-footer h4 {
  color: #1A237E;
}
.material-card.Indigo .mc-footer a {
  background-color: #1A237E;
}
.material-card.Indigo.mc-active .mc-content {
  background-color: #E8EAF6;
}
.material-card.Indigo.mc-active .mc-footer {
  background-color: #C5CAE9;
}
.material-card.Indigo.mc-active .mc-btn-action {
  border-color: #E8EAF6;
}
.material-card.Blue h2 {
  background-color: #2196F3;
}
.material-card.Blue h2:after {
  border-top-color: #2196F3;
  border-right-color: #2196F3;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Blue h2:before {
  border-top-color: transparent;
  border-right-color: #0D47A1;
  border-bottom-color: #0D47A1;
  border-left-color: transparent;
}
.material-card.Blue.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #2196F3;
  border-bottom-color: #2196F3;
  border-left-color: transparent;
}
.material-card.Blue.mc-active h2:after {
  border-top-color: #0D47A1;
  border-right-color: #0D47A1;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Blue .mc-btn-action {
  background-color: #2196F3;
}
.material-card.Blue .mc-btn-action:hover {
  background-color: #0D47A1;
}
.material-card.Blue .mc-footer h4 {
  color: #0D47A1;
}
.material-card.Blue .mc-footer a {
  background-color: #0D47A1;
}
.material-card.Blue.mc-active .mc-content {
  background-color: #E3F2FD;
}
.material-card.Blue.mc-active .mc-footer {
  background-color: #BBDEFB;
}
.material-card.Blue.mc-active .mc-btn-action {
  border-color: #E3F2FD;
}
.material-card.Light-Blue h2 {
  background-color: #03A9F4;
}
.material-card.Light-Blue h2:after {
  border-top-color: #03A9F4;
  border-right-color: #03A9F4;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Light-Blue h2:before {
  border-top-color: transparent;
  border-right-color: #01579B;
  border-bottom-color: #01579B;
  border-left-color: transparent;
}
.material-card.Light-Blue.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #03A9F4;
  border-bottom-color: #03A9F4;
  border-left-color: transparent;
}
.material-card.Light-Blue.mc-active h2:after {
  border-top-color: #01579B;
  border-right-color: #01579B;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Light-Blue .mc-btn-action {
  background-color: #03A9F4;
}
.material-card.Light-Blue .mc-btn-action:hover {
  background-color: #01579B;
}
.material-card.Light-Blue .mc-footer h4 {
  color: #01579B;
}
.material-card.Light-Blue .mc-footer a {
  background-color: #01579B;
}
.material-card.Light-Blue.mc-active .mc-content {
  background-color: #E1F5FE;
}
.material-card.Light-Blue.mc-active .mc-footer {
  background-color: #B3E5FC;
}
.material-card.Light-Blue.mc-active .mc-btn-action {
  border-color: #E1F5FE;
}
.material-card.Cyan h2 {
  background-color: #00BCD4;
}
.material-card.Cyan h2:after {
  border-top-color: #00BCD4;
  border-right-color: #00BCD4;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Cyan h2:before {
  border-top-color: transparent;
  border-right-color: #006064;
  border-bottom-color: #006064;
  border-left-color: transparent;
}
.material-card.Cyan.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #00BCD4;
  border-bottom-color: #00BCD4;
  border-left-color: transparent;
}
.material-card.Cyan.mc-active h2:after {
  border-top-color: #006064;
  border-right-color: #006064;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Cyan .mc-btn-action {
  background-color: #00BCD4;
}
.material-card.Cyan .mc-btn-action:hover {
  background-color: #006064;
}
.material-card.Cyan .mc-footer h4 {
  color: #006064;
}
.material-card.Cyan .mc-footer a {
  background-color: #006064;
}
.material-card.Cyan.mc-active .mc-content {
  background-color: #E0F7FA;
}
.material-card.Cyan.mc-active .mc-footer {
  background-color: #B2EBF2;
}
.material-card.Cyan.mc-active .mc-btn-action {
  border-color: #E0F7FA;
}
.material-card.Teal h2 {
  background-color: #009688;
}
.material-card.Teal h2:after {
  border-top-color: #009688;
  border-right-color: #009688;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Teal h2:before {
  border-top-color: transparent;
  border-right-color: #004D40;
  border-bottom-color: #004D40;
  border-left-color: transparent;
}
.material-card.Teal.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #009688;
  border-bottom-color: #009688;
  border-left-color: transparent;
}
.material-card.Teal.mc-active h2:after {
  border-top-color: #004D40;
  border-right-color: #004D40;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Teal .mc-btn-action {
  background-color: #009688;
}
.material-card.Teal .mc-btn-action:hover {
  background-color: #004D40;
}
.material-card.Teal .mc-footer h4 {
  color: #004D40;
}
.material-card.Teal .mc-footer a {
  background-color: #004D40;
}
.material-card.Teal.mc-active .mc-content {
  background-color: #E0F2F1;
}
.material-card.Teal.mc-active .mc-footer {
  background-color: #B2DFDB;
}
.material-card.Teal.mc-active .mc-btn-action {
  border-color: #E0F2F1;
}
.material-card.Green h2 {
  background-color: #4CAF50;
}
.material-card.Green h2:after {
  border-top-color: #4CAF50;
  border-right-color: #4CAF50;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Green h2:before {
  border-top-color: transparent;
  border-right-color: #1B5E20;
  border-bottom-color: #1B5E20;
  border-left-color: transparent;
}
.material-card.Green.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #4CAF50;
  border-bottom-color: #4CAF50;
  border-left-color: transparent;
}
.material-card.Green.mc-active h2:after {
  border-top-color: #1B5E20;
  border-right-color: #1B5E20;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Green .mc-btn-action {
  background-color: #4CAF50;
}
.material-card.Green .mc-btn-action:hover {
  background-color: #1B5E20;
}
.material-card.Green .mc-footer h4 {
  color: #1B5E20;
}
.material-card.Green .mc-footer a {
  background-color: #1B5E20;
}
.material-card.Green.mc-active .mc-content {
  background-color: #E8F5E9;
}
.material-card.Green.mc-active .mc-footer {
  background-color: #C8E6C9;
}
.material-card.Green.mc-active .mc-btn-action {
  border-color: #E8F5E9;
}
.material-card.Light-Green h2 {
  background-color: #255e61;
}
.material-card.Light-Green h2:after {
  border-top-color: #255e61;
  border-right-color: #255e61;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Light-Green h2:before {
  border-top-color: transparent;
  border-right-color: #33691E;
  border-bottom-color: #33691E;
  border-left-color: transparent;
}
.material-card.Light-Green h2:before {
    border-top-color: transparent;
    border-right-color: #113435;
    border-bottom-color: #113435;
    border-left-color: transparent;
}
article.material-card.Light-Green.mc-active h2:before {
    display: none;
}
.material-card.Light-Green.mc-active h2:after {
    border-top-color: #123435;
    border-right-color: #123435;
    border-bottom-color: transparent;
    border-left-color: transparent;
}
.material-card.Light-Green .mc-btn-action {
    background-color: #255e61;
    border: 3px solid #e4ff55;
    color: #e4ff55 !important;
}
.mc-content h1 {
    font-size: 25px;
}
.material-card.Light-Green .mc-btn-action:hover {
  background-color: #33691E;
}
.material-card.Light-Green .mc-footer h4 {
  color: #33691E;
}
.material-card.Light-Green .mc-footer a {
  background-color: #33691E;
}
.material-card.Light-Green.mc-active .mc-content {
  background-color: #F1F8E9;
}
.material-card.Light-Green.mc-active .mc-footer {
  background-color: #DCEDC8;
}
.material-card.Light-Green.mc-active .mc-btn-action {
  border-color: #F1F8E9;
}
.material-card.Lime h2 {
  background-color: #CDDC39;
}
.material-card.Lime h2:after {
  border-top-color: #CDDC39;
  border-right-color: #CDDC39;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Lime h2:before {
  border-top-color: transparent;
  border-right-color: #827717;
  border-bottom-color: #827717;
  border-left-color: transparent;
}
.material-card.Lime.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #CDDC39;
  border-bottom-color: #CDDC39;
  border-left-color: transparent;
}
.material-card.Lime.mc-active h2:after {
  border-top-color: #827717;
  border-right-color: #827717;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Lime .mc-btn-action {
  background-color: #CDDC39;
}
.material-card.Lime .mc-btn-action:hover {
  background-color: #827717;
}
.material-card.Lime .mc-footer h4 {
  color: #827717;
}
.material-card.Lime .mc-footer a {
  background-color: #827717;
}
.material-card.Lime.mc-active .mc-content {
  background-color: #F9FBE7;
}
.material-card.Lime.mc-active .mc-footer {
  background-color: #F0F4C3;
}
.material-card.Lime.mc-active .mc-btn-action {
  border-color: #F9FBE7;
}
.material-card.Yellow h2 {
  background-color: #FFEB3B;
}
.material-card.Yellow h2:after {
  border-top-color: #FFEB3B;
  border-right-color: #FFEB3B;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Yellow h2:before {
  border-top-color: transparent;
  border-right-color: #F57F17;
  border-bottom-color: #F57F17;
  border-left-color: transparent;
}
.material-card.Yellow.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #FFEB3B;
  border-bottom-color: #FFEB3B;
  border-left-color: transparent;
}
.material-card.Yellow.mc-active h2:after {
  border-top-color: #F57F17;
  border-right-color: #F57F17;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Yellow .mc-btn-action {
  background-color: #FFEB3B;
}
.material-card.Yellow .mc-btn-action:hover {
  background-color: #F57F17;
}
.material-card.Yellow .mc-footer h4 {
  color: #F57F17;
}
.material-card.Yellow .mc-footer a {
  background-color: #F57F17;
}
.material-card.Yellow.mc-active .mc-content {
  background-color: #FFFDE7;
}
.material-card.Yellow.mc-active .mc-footer {
  background-color: #FFF9C4;
}
.material-card.Yellow.mc-active .mc-btn-action {
  border-color: #FFFDE7;
}
.material-card.Amber h2 {
  background-color: #FFC107;
}
.material-card.Amber h2:after {
  border-top-color: #FFC107;
  border-right-color: #FFC107;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Amber h2:before {
  border-top-color: transparent;
  border-right-color: #FF6F00;
  border-bottom-color: #FF6F00;
  border-left-color: transparent;
}
.material-card.Amber.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #FFC107;
  border-bottom-color: #FFC107;
  border-left-color: transparent;
}
.material-card.Amber.mc-active h2:after {
  border-top-color: #FF6F00;
  border-right-color: #FF6F00;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Amber .mc-btn-action {
  background-color: #FFC107;
}
.material-card.Amber .mc-btn-action:hover {
  background-color: #FF6F00;
}
.material-card.Amber .mc-footer h4 {
  color: #FF6F00;
}
.material-card.Amber .mc-footer a {
  background-color: #FF6F00;
}
.material-card.Amber.mc-active .mc-content {
  background-color: #FFF8E1;
}
.material-card.Amber.mc-active .mc-footer {
  background-color: #FFECB3;
}
.material-card.Amber.mc-active .mc-btn-action {
  border-color: #FFF8E1;
}
.material-card.Orange h2 {
  background-color: #FF9800;
}
.material-card.Orange h2:after {
  border-top-color: #FF9800;
  border-right-color: #FF9800;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Orange h2:before {
  border-top-color: transparent;
  border-right-color: #E65100;
  border-bottom-color: #E65100;
  border-left-color: transparent;
}
.material-card.Orange.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #FF9800;
  border-bottom-color: #FF9800;
  border-left-color: transparent;
}
.material-card.Orange.mc-active h2:after {
  border-top-color: #E65100;
  border-right-color: #E65100;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Orange .mc-btn-action {
  background-color: #FF9800;
}
.material-card.Orange .mc-btn-action:hover {
  background-color: #E65100;
}
.material-card.Orange .mc-footer h4 {
  color: #E65100;
}
.material-card.Orange .mc-footer a {
  background-color: #E65100;
}
.material-card.Orange.mc-active .mc-content {
  background-color: #FFF3E0;
}
.material-card.Orange.mc-active .mc-footer {
  background-color: #FFE0B2;
}
.material-card.Orange.mc-active .mc-btn-action {
  border-color: #FFF3E0;
}
.material-card.Deep-Orange h2 {
  background-color: #FF5722;
}
.material-card.Deep-Orange h2:after {
  border-top-color: #FF5722;
  border-right-color: #FF5722;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Deep-Orange h2:before {
  border-top-color: transparent;
  border-right-color: #BF360C;
  border-bottom-color: #BF360C;
  border-left-color: transparent;
}
.material-card.Deep-Orange.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #FF5722;
  border-bottom-color: #FF5722;
  border-left-color: transparent;
}
.material-card.Deep-Orange.mc-active h2:after {
  border-top-color: #BF360C;
  border-right-color: #BF360C;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Deep-Orange .mc-btn-action {
  background-color: #FF5722;
}
.material-card.Deep-Orange .mc-btn-action:hover {
  background-color: #BF360C;
}
.material-card.Deep-Orange .mc-footer h4 {
  color: #BF360C;
}
.material-card.Deep-Orange .mc-footer a {
  background-color: #BF360C;
}
.material-card.Deep-Orange.mc-active .mc-content {
  background-color: #FBE9E7;
}
.material-card.Deep-Orange.mc-active .mc-footer {
  background-color: #FFCCBC;
}
.material-card.Deep-Orange.mc-active .mc-btn-action {
  border-color: #FBE9E7;
}
.material-card.Brown h2 {
  background-color: #795548;
}
.material-card.Brown h2:after {
  border-top-color: #795548;
  border-right-color: #795548;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Brown h2:before {
  border-top-color: transparent;
  border-right-color: #3E2723;
  border-bottom-color: #3E2723;
  border-left-color: transparent;
}
.material-card.Brown.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #795548;
  border-bottom-color: #795548;
  border-left-color: transparent;
}
.material-card.Brown.mc-active h2:after {
  border-top-color: #3E2723;
  border-right-color: #3E2723;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Brown .mc-btn-action {
  background-color: #795548;
}
.material-card.Brown .mc-btn-action:hover {
  background-color: #3E2723;
}
.material-card.Brown .mc-footer h4 {
  color: #3E2723;
}
.material-card.Brown .mc-footer a {
  background-color: #3E2723;
}
.material-card.Brown.mc-active .mc-content {
  background-color: #EFEBE9;
}
.material-card.Brown.mc-active .mc-footer {
  background-color: #D7CCC8;
}
.material-card.Brown.mc-active .mc-btn-action {
  border-color: #EFEBE9;
}
.material-card.Grey h2 {
  background-color: #9E9E9E;
}
.material-card.Grey h2:after {
  border-top-color: #9E9E9E;
  border-right-color: #9E9E9E;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Grey h2:before {
  border-top-color: transparent;
  border-right-color: #212121;
  border-bottom-color: #212121;
  border-left-color: transparent;
}
.material-card.Grey.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #9E9E9E;
  border-bottom-color: #9E9E9E;
  border-left-color: transparent;
}
.material-card.Grey.mc-active h2:after {
  border-top-color: #212121;
  border-right-color: #212121;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Grey .mc-btn-action {
  background-color: #9E9E9E;
}
.material-card.Grey .mc-btn-action:hover {
  background-color: #212121;
}
.material-card.Grey .mc-footer h4 {
  color: #212121;
}
.material-card.Grey .mc-footer a {
  background-color: #212121;
}
.material-card.Grey.mc-active .mc-content {
  background-color: #FAFAFA;
}
.material-card.Grey.mc-active .mc-footer {
  background-color: #F5F5F5;
}
.material-card.Grey.mc-active .mc-btn-action {
  border-color: #FAFAFA;
}
.material-card.Blue-Grey h2 {
  background-color: #607D8B;
}
.material-card.Blue-Grey h2:after {
  border-top-color: #607D8B;
  border-right-color: #607D8B;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Blue-Grey h2:before {
  border-top-color: transparent;
  border-right-color: #263238;
  border-bottom-color: #263238;
  border-left-color: transparent;
}
.material-card.Blue-Grey.mc-active h2:before {
  border-top-color: transparent;
  border-right-color: #607D8B;
  border-bottom-color: #607D8B;
  border-left-color: transparent;
}
.material-card.Blue-Grey.mc-active h2:after {
  border-top-color: #263238;
  border-right-color: #263238;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Blue-Grey .mc-btn-action {
  background-color: #607D8B;
}
.material-card.Blue-Grey .mc-btn-action:hover {
  background-color: #263238;
}
.material-card.Blue-Grey .mc-footer h4 {
  color: #263238;
}
.material-card.Blue-Grey .mc-footer a {
  background-color: #263238;
}
.material-card.Blue-Grey.mc-active .mc-content {
  background-color: #ECEFF1;
}
.material-card.Blue-Grey.mc-active .mc-footer {
  background-color: #CFD8DC;
}
.material-card.Blue-Grey.mc-active .mc-btn-action {
  border-color: #ECEFF1;
}

h1,
h2,
h3 {
  font-weight: 200;
}

.total-approval-sec,.approval-time-chart,.request-status-chart,.approval-count-chart {
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 0 10px #e6e6e6;
    height: 100%;
}
.total-approval-sec {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    
}
.approval-time-wrap.row > div {
    margin-bottom: 25px;
}

.legends {
    width: 100% !important;
    font-weight: 700;
    color: #255e61;
    font-size: 18px;
    margin-bottom: 10px;
}
.inheading{
    background-color:#255e61;
    color:#fff;
    padding:8px;
}
</style>

    @if(\Auth::user()->id == 1)
        <div class="approval-time-wrap row">
            <div class="col-lg-12">
                <h2>SAP Change Request Dashboard</h2>
            </div>
            <div class="col-md-6">
                <div id="pie-1">
                </div>
            </div>
            <div class="col-md-6">
                <div id="bar-1">
                </div>
            </div>
            
            <!-- Search at a glance -->
            <div class="col-lg-12">
                <legend>
                    Search
                </legend>
            </div>
    </div> 
        <form method='post' id='srchFrm'>
          <input type="hidden" name="take" id="take">
          <input type="hidden" name="skip" id="skip">
          <input type="hidden" id="position" value="1">
          <input type="hidden" id="noOfPages" value="0">
            <div class="row">
                <div class="col-lg-4 mt-2">
                    <input type="text" name="req_id" id="req_id" class="form-control" placeholder="Request ID">
                </div>
                <div class="col-lg-4 pt-2">
                    <select name="module_id" id="module_id" class="form-control select2bs4" data-placeholder='Select Module'>
                        <option value=""></option>   
                    @foreach($modules as $each)
                            <option value="{{ $each['id'] }}">{{ $each['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 pt-2">
                    <select name="tcode_id" id="tcode_id" class="form-control select2bs4" placeholder='Select TCode'>
                        <option value="">--SELECT MODULE FIRST--</option>
                    </select>
                </div>
                <div class="col-lg-4 mt-2">
                    <input type="text" name="user_id" class="form-control" placeholder="User">
                </div>
                <div class="col-lg-4 mt-2">
                    <input type="text" name="fromDate" id="from" class="form-control" placeholder="From Date">
                </div>
                <div class="col-lg-4 mt-2">
                    <input type="text" name="toDate" id="to" class="form-control" placeholder="To Date">
                </div>
                
                <div class="col-lg-4 mt-2">
                    <button name="search_btn" id="searchBtn" class="btn btn-primary">Search</button>
                    &nbsp;<button name="reset-btn" id="reset-btn" type="button" class="btn btn-primary">Reset</button>
                </div>
            </div>
        </form>
        <div class="row">
            <div class='col-lg-12'>
             
                <div class="row active-with-click" id="view-result">
                  
                </div>
               
           </div>
        </div>
       
    @endif
    <!-- Description modal -->
    <div class="modal fade" id="desc-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detailed Description</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                  <div id="desc"></div>
                </div>
               
            </div>
            </div>
    </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script type="text/javascript">
/** Reset Form */
$(document).on('click','#reset-btn', (e) => {
  e.preventDefault();
  $("#srchFrm")[0].reset();
  $("#tcode_id").val('').trigger('change');
  $("#module_id").val('').trigger('change');
  customLoader(0)
  
})

/** On Load Set Take Skip Initial values */
$("#skip").val(0);
$("#take").val(3);

/** Search Form */
$("#searchBtn").on('click', (e) => {
    e.preventDefault();
        loadRequests();
});

$("#from").datepicker({ maxDate: 0, changeMonth:true, changeYear:true, dateFormat: 'yy-mm-dd'});
$("#to").datepicker({ maxDate: 0, changeMonth:true, changeYear:true, dateFormat: 'yy-mm-dd'});
loadPieChart1();
loadBarChart2();
loadRequests();
/* Load Stages Bar chart */
function loadBarChart2() {

$.ajax({
    url:route('fetch.stage.bar'),
    data:null,
    type:"GET",
    error:(r) => {
        toastr.error("Error");
    },
    success: (r) => {

        if(r.data) {
            console.log(r.data)
            renderStagesBarChart(r.data);
        }
    }
})
}

/* Load Pie 1 */
function loadPieChart1() {

    $.ajax({
        url:route('fetch.dev.sap.modules'),
        data:null,
        type:"GET",
        error:(r) => {
            toastr.error("Error");
        },
        success: (r) => {
            console.log(r.drilleddata);
            if(r.data) {
                renderPieChart(r.data, r.drilleddata);
            }
        }
    })
}


// Create the chart
function renderPieChart(dataset, drilleddata) {
        Highcharts.chart('pie-1', {
                chart: {
                    type: 'pie'
                },
                exporting: {
                    enabled: false
                },
                title: {
                    text: 'Module wise Requests'
                },
                subtitle: {
                    text: 'Viewing Overall'
                },

                accessibility: {
                    announceNewData: {
                    enabled: true
                    },
                    point: {
                    valueSuffix: ''
                    }
                },

                plotOptions: {
                    series: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.y}'
                    }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
                },

                series: [
                    {
                    name: "Requests",
                    colorByPoint: true,
                    data: dataset
                    }
                ],
                drilldown: {
                    series: drilleddata

                }
        });
}

/** Load Stages Chart */
function renderStagesBarChart(dataset) {
    Highcharts.chart('bar-1', {
  chart: {
    type: 'column'
  },
  exporting: {
    enabled: false
  },
  title: {
    text: 'Overall Stage wise Request'
  },
  subtitle: {
    text: ''
  },
  xAxis: {
    categories: [
      'REQUIREMENT',
      'APPROVER',
      'DEVELOPMENT',
      'UAT',
      'PRODUCTION'
    ],
    crosshair: true
  },
  yAxis: {
    min: 0,
    title: {
      text: 'Count'
    }
  },
  tooltip: {
    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
      '<td style="padding:0"><b>{point.y}</b></td></tr>',
    footerFormat: '</table>',
    shared: true,
    useHTML: true
  },
  plotOptions: {
    column: {
      pointPadding: 0,
      borderWidth: 1
    }
  },
  series: [{
    name: 'Requests',
    data: dataset

  }]
});
}


function loadRequests() {

    $.ajax({
    url:route('fetch.dev.dashboard.requests'),
    data:$("#srchFrm").serialize(),
    type:"GET",
    error:(r) => {
        toastr.error("Error");
    },
    success: (r) => {
        if(r.data) {
            console.log(r)
            var html = ``;
            const take = $("#take").val();
            html = renderHTML(r.data, r.totalCount, take)
            $("#view-result").html(html)
        } else {
          toastr.error('Something went wrong');
        }
    }
})
}

function renderHTML(data, totalCount, take) {
let html = ``;
let logs = '';

      
        $.each(data, (i) => {
            $.each(data[i].logs, (j) => {
              logs += `<p class='shadow-sm' style='padding-top:15px; font-size:13px !important;'><i class='fas fa-angle-double-right'></i> <strong>${data[i].logs[j].creator.first_name}</strong> has moved this task from <strong>${data[i].logs[j].from_stage.name}</strong> to <strong>${data[i].logs[j].to_stage.name}</strong> as on <strong>${data[i].logs[j].created_at}</strong></p>`;
            });

            html += `<div class="col-md-4 col-sm-6 col-xs-12 mt-4">
                        <article class="material-card Light-Green">
                            <h2>
                                <span>${data[i].creator.first_name}</span>
                                <strong>
                                    <i class="fas fa-user-tag"></i>
                                    Operations
                                </strong>
                            </h2>
                            <div class="mc-content shadow" style="min-height:auto">
                              <div style='padding: 7px;
                        height: 100px;
                        border-bottom: 1px solid #ccc;
                        background-color: #fff;'><h1>DEV/TEST/${data[i].id}</h1>&nbsp;&nbsp;<small>${data[i].created_at}</small></div>
                      
                                        <div class='row' style='position:relative; top:5%; font-size: 13px;
                        padding: 5px;
                        margin: 0;'>
                        <div class='col-lg-12'>
                          <h6 class='inheading text-bold'>Employee Details</h6></div>
                                <div class='col-lg-4'>
                                    <label> SAP Code </label> <br>
                                    <span class='badge badge-primary'>${data[i].creator.sap_code} </span>
                                </div>
                                <div class='col-lg-4'>
                                    <label> Designation </label> <br>
                                    <span> - </span>
                                </div>
                                <div class='col-lg-4'>
                                    <label> Department </label> <br>
                                    <span> ${(data[i].creator.departments !== undefined) ? data[i].creator.departments.department_name : '-'} </span>
                                </div>
                                <div class='col-lg-12 mt-4'><h6 class='inheading text-bold'>Requirements</h6></div>
                              
                                <div class='col-lg-4'>
                                    <label> Module </label> <br>
                                    <span> ${data[i].permission.name} </span>
                                </div>
                                <div class='col-lg-4'>
                                    <label> Tcode </label> <br>
                                    <span> ${data[i].tcode.t_code} </span>
                                </div>
                                <div class='col-lg-4'>
                                    <label> Request Stage </label> <br>
                                    <span class='badge badge-primary'>${data[i].stage.name} </span>
                                </div>
                                <div class='col-lg-12 mt-2'>
                                    <a href='javascript:void(0)' class='badge badge-primary text-white p-1 m-0' style='padding:4px !important' onclick='viewDescription("${data[i].description}")'> Read More </a>
                                </div>
                            </div>
                              
                                <div class="mc-description">
                              
                                    <h2><i class='fa fa-history'></i> Activity Logs </h2>
                                    <div class='wrapper' style='max-height: 200px; overflow:auto'>
                                        ${logs}
                                    </div>
                                </div>
                            </div>
                            <a class="mc-btn-action" onClick="trig(this)">
                                <i class="fa fa-bars"></i>
                            </a>
                            <div class="mc-footer d-none">
                                
                            </div>
                        </article>
                    </div>`;

                  });
                      pages = 
                      html += `<div class='col-lg-12'><nav aria-label="Page navigation">
                          <ul class="pagination">
                            <li class="page-item">
                              <a class="page-link" href='javascript:void(0)' onclick="moveLeft()" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                              </a>
                            </li>
                            
                            ${ generatePagination(totalCount, take) }
                            <li class="page-item">
                              <a class="page-link" href='javascript:void(0)' onclick="moveRight()" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                              </a>
                            </li>
                          </ul>
                      </nav></div>`;

                return html;
}

function viewDescription(desc) {

    $("#desc").html(`<h5>${desc}</h5>`)
    $("#desc-modal").modal('show');
}

function generatePagination(count, take) {
  let pages = count / take;
  if(count % take == 0) {
    pages = count / take;
  } else {
    // 5 or 7 or 8 or 10
    pages = Math.round(pages);
  }

  let html = ``;
  for(let i=0; i<pages; i++) {
    html += `<li class="page-item"><a class="page-link" href="javascript:void(0)" onClick="navigate('${i+1}')">${i+1}</a></li>`
  };
  $("#noOfPages").val(pages)
  return html;
}
</script>

<script>
    

    function trig(obj) {
   
            var card = $(obj).parent('.material-card');
            var icon = $(obj).children('i');
            icon.addClass('fa-spin-fast');

            if (card.hasClass('mc-active')) {
              
                card.removeClass('mc-active');

                window.setTimeout(function() {
                    icon
                        .removeClass('fa-arrow-left')
                        .removeClass('fa-spin-fast')
                        .addClass('fa-bars');

                }, 800);
            } else {
                
                card.addClass('mc-active');

                window.setTimeout(function() {
                    icon
                        .removeClass('fa-bars')
                        .removeClass('fa-spin-fast')
                        .addClass('fa-arrow-left');

                }, 800);
            }
        };


        function navigate(position) {
            let take = parseInt($("#take").val());
            let newSkip = parseInt($("#skip").val());
            if(position == 1 || position < 0) {
              newSkip = 0
            } else if(position > 1) {
              newSkip = (position-1) * take;
            }
              $("#skip").val(newSkip);
              $("#position").val(position)
              loadRequests();
        }

        function moveRight() {
            var currentPos = parseInt($("#position").val());
            var noOfPages = parseInt($("#noOfPages").val());
            if(currentPos < noOfPages) {
              navigate(++currentPos)
            }
        }

        function moveLeft() {
          var currentPos = parseInt($("#position").val());
          console.log(currentPos)
          if(currentPos>1){
            navigate(--currentPos)
          }
          
        }




</script>
@stop

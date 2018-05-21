import { Component, OnInit } from '@angular/core';
import { WeatherService } from './weather.service';
import { ThemeModule } from '../../../@theme/theme.module';
import { Chart } from 'chart.js';


@Component({
  selector: 'app-magcharts',
  templateUrl: './magcharts.component.html',
  styleUrls: ['./magcharts.component.scss']
})
export class MagchartsComponent implements OnInit {

  chart = []; // This will hold our chart info
  constructor(private _weather: WeatherService) {}

  type = 'Year';
  types = ['week', 'month', 'year'];
 
  
  ngOnInit() {
    this._weather.dailyForecast()
      .subscribe(res => {
        let temp_max = res['list'].map(res => res.main.temp_max);
		let temp_min = res['list'].map(res => res.main.temp_min);
		let alldates = res['list'].map(res => res.dt)

		let weatherDates = []
		alldates.forEach((res) => {
			this.chart = new Chart('canvas', {
			  type: 'line',
			  data: {
				labels: weatherDates,
				datasets: [
				  { 
					data: temp_max,
					borderColor: "#3cba9f",
					fill: false
				  },
				  { 
					data: temp_min,
					borderColor: "#ffcc00",
					fill: false
				  },
				]
			  },
			  options: {
				legend: {
				  display: false
				},
				scales: {
				  xAxes: [{
					display: true
				  }],
				  yAxes: [{
					display: true
				  }],
				}
			  }
			});
			let jsdate = new Date(res * 1000)
			weatherDates.push(jsdate.toLocaleTimeString('en', { year: 'numeric', month: 'short', day: 'numeric' }))
		})
      })
  }
}//end magcharts

import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import 'rxjs/add/operator/map';

@Injectable()
export class WeatherService {
  constructor(private _http: HttpClient) { }

  fetchUrl = "../../../../assets/json/weather.json";

  dailyForecast() {
    return this._http.get(this.fetchUrl)
      .map(result => result)
  }
}

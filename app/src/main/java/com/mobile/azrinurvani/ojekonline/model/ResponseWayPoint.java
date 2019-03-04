package com.mobile.azrinurvani.ojekonline.model;

import com.google.gson.annotations.SerializedName;

import java.util.List;


public class ResponseWayPoint{

	@SerializedName("routes")
	private List<RoutesItem> routes;

	@SerializedName("geocoded_waypoints")
	private List<GeocodedWaypointsItem> geocodedWaypoints;

	@SerializedName("status")
	private String status;

	public void setRoutes(List<RoutesItem> routes){
		this.routes = routes;
	}

	public List<RoutesItem> getRoutes(){
		return routes;
	}

	public void setGeocodedWaypoints(List<GeocodedWaypointsItem> geocodedWaypoints){
		this.geocodedWaypoints = geocodedWaypoints;
	}

	public List<GeocodedWaypointsItem> getGeocodedWaypoints(){
		return geocodedWaypoints;
	}

	public void setStatus(String status){
		this.status = status;
	}

	public String getStatus(){
		return status;
	}
}
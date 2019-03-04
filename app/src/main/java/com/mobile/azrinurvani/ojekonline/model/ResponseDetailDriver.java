package com.mobile.azrinurvani.ojekonline.model;

import java.util.List;
import com.google.gson.annotations.SerializedName;

public class ResponseDetailDriver{

	@SerializedName("result")
	private String result;

	@SerializedName("msg")
	private String msg;

	@SerializedName("data")
	private List<DataDetailDriver> data;

	public void setResult(String result){
		this.result = result;
	}

	public String getResult(){
		return result;
	}

	public void setMsg(String msg){
		this.msg = msg;
	}

	public String getMsg(){
		return msg;
	}

	public void setData(List<DataDetailDriver> data){
		this.data = data;
	}

	public List<DataDetailDriver> getData(){
		return data;
	}
}
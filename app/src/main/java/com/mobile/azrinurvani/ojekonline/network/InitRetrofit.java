package com.mobile.azrinurvani.ojekonline.network;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.mobile.azrinurvani.ojekonline.helper.MyContants;

import java.util.concurrent.TimeUnit;

import okhttp3.OkHttpClient;
import okhttp3.logging.HttpLoggingInterceptor;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class InitRetrofit {


    //inizialisasi sebuah library untuk accesss data dari server
    //library yang digunakan adalah retrofit dari square

    public static Retrofit setInit() {
        HttpLoggingInterceptor interceptor = new HttpLoggingInterceptor();
        interceptor.setLevel(HttpLoggingInterceptor.Level.BODY);

           OkHttpClient client = new OkHttpClient.Builder()
                   .addInterceptor(interceptor)
                   .retryOnConnectionFailure(true)
                   .connectTimeout(45, TimeUnit.SECONDS)
                   .build();
        Gson gson = new GsonBuilder()
                .setLenient()
                .create();

        return new Retrofit.Builder().baseUrl(MyContants.BASE_URL)
                .client(client)
                .addConverterFactory(GsonConverterFactory.create())
                .build();

    }
    public static RestApi getInstance(){

        return setInit().create(RestApi.class);


    }
    public static Retrofit setInitGoogle() {

        return new Retrofit.Builder().baseUrl(MyContants.BASE_MAP_URL)
                .addConverterFactory(GsonConverterFactory.create())
                .build();

    }
    public static RestApi getInstanceGoogle(){

        return setInitGoogle().create(RestApi.class);

    }
}
